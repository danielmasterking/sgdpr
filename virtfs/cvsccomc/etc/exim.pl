
BEGIN {
    unshift @INC, '/etc/exim/perl';
}

my $hasmd5;

sub loadmd5 {
    if ( defined $hasmd5 ) { return; }
    eval {
#require Digest::Perl::MD5;
        $hasmd5 = 1;
    };
}

sub checkrelayhost {
    my ($hostaddress) = @_;

    if ( $hostaddress eq "127.0.0.1" ) { return 1; }

    open( RELAYHOSTS, "/etc/relayhosts" );
    while (<RELAYHOSTS>) {
        s/\n//g;
        next if ( $_ eq "" );
        if ( $hostaddress eq $_ ) {
            close(RELAYHOSTS);
            return 1;
        }
    }
    close(RELAYHOSTS);
    return (0);

}

sub getfilterfile {
    my ($user)   = @_;
    my ($domain) = getusersdomain($user);
    return ("/etc/vfilters/${domain}");
}

sub hasfilterfile {
    my ($user)   = @_;
    my ($domain) = getusersdomain($user);
    if ( ${domain} eq "" ) { return (0); }
    if ( !-d "/etc/vfilters/${domain}" && -e "/etc/vfilters/${domain}" ) { return (1); }
    return (0);
}

sub checkvalias {
    my ( $domain, $local_part ) = @_;
    my ($hasval)        = 0;
    my ($autoresponder) = 0;
    open( VAL, "/etc/valiases/$domain" );
    while (<VAL>) {
        if ( $autoresponder && beginmatch( $_, "*:" ) ) {
            if (/:fail:/) {

                #stop processing the message as we already have
                #given an autoresponse and we do not want to send
                #a failure message
                $hasval = 1;
                last();
            }
        }
        elsif ( beginmatch( $_, "${local_part}\@${domain}:" ) ) {
            my $defi;
            ( undef, $defi ) = split( /: /, $_ );
            my (@DESTS) = split( /\,/, $defi );
            foreach my $dest (@DESTS) {
                if ( $dest =~ /\/autorespond/ ) {
                    $autoresponder = 1;
                }
                else {
                    $hasval = 1;
                }
            }
            if ( !$autoresponder ) {
                last;
            }
        }
    }
    close(VAL);

    return ($hasval);

}

sub gensaheader_virtual {
    my ($domain)  = @_;
    my ($owner)   = getdomainowner($domain);
    my ($spamkey) = getspamkey($owner);
    return "X-Spam-Exim: $spamkey\n";
}

sub gensaheader {
    my ($owner)   = @_;
    my ($spamkey) = getspamkey($owner);
    return "X-Spam-Exim: $spamkey\n";
}

sub getspamkey {
    my ($user)    = @_;
    my ($homedir) = gethomedir($user);
    my ($spamkey);

    if ( -e "${homedir}/.spamkey" ) {
        open( SPAMKEY, "${homedir}/.spamkey" );
        $spamkey = <SPAMKEY>;
        close(SPAMKEY);
        return ($spamkey);
    }
    else {
        if ( $> == 0 ) {
            my $pid;
            if ( !( $pid = fork() ) ) {
                &setuids($user);
                open( RANDOM, "/dev/urandom" );
                read RANDOM, $spamkey, 4096;
                close(RANDOM);
                $spamkey =~ s/\W//g;
                $spamkey = substr( $spamkey, 0, 24 );
                open( SPAMKEY, ">${homedir}/.spamkey" );
                chmod( 0600, "${homedir}/.spamkey" );
                print SPAMKEY $spamkey;
                close(SPAMKEY);
                exit();
            }
            waitpid( $pid, 0 );
            open( SPAMKEY, "${homedir}/.spamkey" );
            $spamkey = <SPAMKEY>;
            close(SPAMKEY);
            return ($spamkey);
        }
        else {
            open( RANDOM, "/dev/urandom" );
            read RANDOM, $spamkey, 4096;
            close(RANDOM);
            $spamkey =~ s/\W//g;
            $spamkey = substr( $spamkey, 0, 24 );
            open( SPAMKEY, ">${homedir}/.spamkey" );
            chmod( 0600, "${homedir}/.spamkey" );
            print SPAMKEY $spamkey;
            close(SPAMKEY);
            return ($spamkey);
        }

    }
}

sub checksa_deliver {
    my ( $domain, $localpart, $received_protocol ) = @_;
    my ($owner)         = getdomainowner($domain);
    my ($homedir)       = gethomedir($owner);
    my ($passwd)        = "${homedir}/etc/${domain}/passwd";
    my ($addressexists) = 0;
    my ($spamkey)       = getspamkey($owner);
    my $headers         = Exim::expand_string('$message_headers');

    if ( $headers =~ /^X-Spam-Exim: ${spamkey}$/m ) {
        return "no";
    }
    if ( $received_protocol eq "local-bsmtp" ) { return "no"; }

    if ( -e $passwd ) {
        open( PASSWD, ${passwd} );
        while (<PASSWD>) {
            if ( beginmatch( $_, "${localpart}:" ) ) { $addressexists = 1; }
        }
        close(PASSWD);
    }
    else {
        return "no";
    }

    if ( $> == 0 ) {
        my $waittime = 1;
        while ( -e "${homedir}/.spamassassinquotatest" ) {
            if ( $waittime == 60 ) { last; }
            $waittime++;
            sleep(1);
        }
        my $pid;
        if ( !( $pid = fork() ) ) {
            umask(0002);
            &setuids($owner);
            open( QUOTATEST, ">${homedir}/.spamassassinquotatest" );
            print QUOTATEST " " x 4096;
            close(QUOTATEST);
            exit();
        }
        waitpid( $pid, 0 );
        if ( !( ( stat("${homedir}/.spamassassinquotatest") )[7] == 4096 ) ) {
            unlink("${homedir}/.spamassassinquotatest");
            return "no";
        }
        unlink("${homedir}/.spamassassinquotatest");
    }

    if ( -e $homedir . "/.spamassassinenable" && $addressexists ) {
        return "yes";
    }
    else {
        return "no";
    }
}

sub check_deliver {
    my ( $domain, $localpart ) = @_;
    my ($owner)         = getdomainowner($domain);
    my ($homedir)       = gethomedir($owner);
    my ($passwd)        = "${homedir}/etc/${domain}/passwd";
    my ($addressexists) = 0;

    if ( -e $passwd ) {
        open( PASSWD, ${passwd} );
        while (<PASSWD>) {
            if ( beginmatch( $_, "${localpart}:" ) ) { $addressexists = 1; }
        }
        close(PASSWD);
    }
    else {
        return "no";
    }

    if ($addressexists) {
        return "yes";
    }
    else {
        return "no";
    }
}

sub check_deliver_spam {
    my ( $domain, $localpart ) = @_;
    my ($owner) = getdomainowner($domain);

    if ( $owner eq "root" ) { return "no"; }

    my ($homedir)       = gethomedir($owner);
    my ($passwd)        = "${homedir}/etc/${domain}/passwd";
    my ($addressexists) = 0;
    my ($isspam)        = 0;

    if ( !-e $homedir . "/.spamassassinboxenable" ) { return "no"; }

    if ( -e $passwd ) {
        open( PASSWD, ${passwd} );
        while (<PASSWD>) {
            if ( beginmatch( $_, "${localpart}:" ) ) { $addressexists = 1; }
        }
        close(PASSWD);
    }
    else {
        return "no";
    }

    my $headers = Exim::expand_string('$message_headers');
    if ( $headers =~ /^X-Spam-Status: Yes/m ) {
        $isspam = 1;
    }

    if ( $addressexists && $isspam ) {
        return "yes";
    }
    else {
        return "no";
    }
}

sub checkusersa {
    my ( $owner, $received_protocol ) = @_;

    if ( $owner eq "root" ) { return "no"; }

    my ($homedir) = gethomedir($owner);
    my ($spamkey) = getspamkey($owner);
    my $headers   = Exim::expand_string('$message_headers');

    if ( $headers =~ /^X-Spam-Exim: ${spamkey}$/m ) {
        return "no";
    }

    if ( $received_protocol eq "local-bsmtp" ) { return "no"; }

    if ( $> == 0 ) {
        my $waittime = 1;
        while ( -e "${homedir}/.spamassassinquotatest" ) {
            if ( $waittime == 60 ) { last; }
            $waittime++;
            sleep(1);
        }
        my $pid;
        if ( !( $pid = fork() ) ) {
            umask(0002);
            &setuids($owner);
            open( QUOTATEST, ">${homedir}/.spamassassinquotatest" );
            print QUOTATEST " " x 4096;
            close(QUOTATEST);
            exit();
        }
        waitpid( $pid, 0 );
        if ( !( ( stat("${homedir}/.spamassassinquotatest") )[7] == 4096 ) ) {
            unlink("${homedir}/.spamassassinquotatest");
            return "no";
        }
        unlink("${homedir}/.spamassassinquotatest");
    }

    if ( -e $homedir . "/.spamassassinenable" ) {
        return "yes";
    }
    else {
        return "no";
    }
}

sub checkuserspambox {
    my ($owner) = @_;

    if ( $owner eq "root" ) { return "no"; }

    my ($homedir) = gethomedir($owner);
    my ($isspam)  = 0;

    my $headers = Exim::expand_string('$message_headers');
    if ( $headers =~ /^X-Spam-Status: Yes/m ) {
        $isspam = 1;
    }

    if ( -e $homedir . "/.spamassassinboxenable" && $isspam ) {
        return "yes";
    }
    else {
        return "no";
    }
}

sub checkspam {
    my $uid              = Exim::expand_string('$originator_uid');
    my $gid              = Exim::expand_string('$originator_gid');
    my $primary_hostname = Exim::expand_string('$primary_hostname');
    my $sender           = Exim::expand_string('$sender_address');
    my $domain;
    my $islocald = 0;

    my @LD;
    open( LD, "/etc/localdomains" );
    @LD = <LD>;
    close(LD);

    #MAILTRAP
    my $safegid = ( getgrnam("mailtrap") )[2];
    if ( $uid >= 99 && $gid >= 99 && $safegid ne $gid && -e "/etc/eximmailtrap" ) {
        die "Gid $gid is not permitted to relay mail";
    }

    #MAILTRAP

    if ( $sender =~ /\@${primary_hostname}/ ) {
        my $tuid = Exim::expand_string('${extract{2}{:}{${lookup passwd{$sender_address}{$value}}}}');
        if ( $uid eq "0" and $tuid ne "0" ) {
            $uid = $tuid;
        }
        $domain = getusersdomain( ( getpwuid($uid) )[0] );
        $islocald = 1;
    }
    else {
        my $sender_domain;
        ( undef, $sender_domain ) = split( /\@/, $sender );
        $domain = $sender_domain;

        foreach my $ldomain (@LD) {
            $ldomain =~ s/\n//g;
            if ( $domain eq $ldomain ) { $islocald = 1; }
        }

        my $tuser = getdomainowner($sender_domain);
        $tuser =~ s/\\//g;
        my $tuid  = Exim::expand_string( '${extract{2}{:}{${lookup passwd{\N' . $tuser . '\N}{$value}}}}' );
        if ( $uid eq "0" and $tuid ne "0" ) {
            $uid = $tuid;
        }
    }

    if ( int($uid) == 99 && -e '/etc/webspam' ) {
        die "Mail sent by user nobody, UID 99, being discarded due to sender restrictions in WHM->Tweak Settings";
    }
    if ( isdemo($uid) ) {
        die "Demo Accounts are not permitted to relay mail.";
    }

    my $headers               = Exim::expand_string('$message_headers');
    my $original_domain       = Exim::expand_string('$original_domain');
    my $sender_address_domain = Exim::expand_string('$sender_address_domain');

    if ( !$islocald ) { return "yes"; }

    #logsmtpbw here
    my $now = time();
    $domain =~ s/[^\w\.\-]//g;

    #we just can't trust user input
    my $message_size = Exim::expand_string('$message_size');

    if ( $domain ne "" ) {
        my $maxmails = 0;

        open( CF, "/var/cpanel/cpanel.config" );
        while (<CF>) {
            next if (/^#/);
            s/\n//g;
            my ( $var, $value ) = split( /=/, $_ );
            if ( $var eq "maxemailsperhour" ) {
                $maxmails = int($value);
            }
        }
        close(CF);

        open( CPM, "/var/cpanel/maxemails" );
        while (<CPM>) {
            s/\n//g;
            my ( $mdomain, $mmax ) = split(/=/);
            if ( $mdomain eq $domain ) {
                $maxmails = int($mmax);
            }
        }
        close(CPM);

        if ( $maxmails > 0 ) {
            my $nummailsinhour = readbacktodate("/usr/local/apache/domlogs/$domain-smtpbytes_log");
            if ( $nummailsinhour > $maxmails ) {
                die "Domain $domain has exceeded the max emails per hour. Message discarded.\n";
            }
        }

        open( DLOG, ">>/usr/local/apache/domlogs/$domain-smtpbytes_log" );
        print DLOG "$now $message_size .\n";
        close(DLOG);
        chmod( 0640, "/usr/local/apache/domlogs/$domain-smtpbytes_log" );
    }

    #end logsmtpbw

    if ( !( $uid == 99 ) ) {

        #If it isn't the nobody user its ok
        return "yes";
    }

    my ($receivedfor);
    my @RECPS;
    my @HEADERS = split( /\n/, $headers );
    my ( $header, $email );
    foreach $header (@HEADERS) {
        if ( $header =~ /^to:/i ) {
            my $line = $header;
            $line =~ s/^to: //ig;
            my @TRECPS = split( /[\,\;]/, $line );
            foreach (@TRECPS) { push( @RECPS, $_ ); }
        }
        if ( $header =~ /^bcc:/i ) {
            my $line = $header;
            $line =~ s/^to: //ig;
            my @TRECPS = split( /[\,\;]/, $line );
            foreach (@TRECPS) { push( @RECPS, $_ ); }
        }
        if ( $header =~ /^cc:/i ) {
            my $line = $header;
            $line =~ s/^to: //ig;
            my @TRECPS = split( /[\,\;]/, $line );
            foreach (@TRECPS) { push( @RECPS, $_ ); }
        }
        if ( $header =~ /\tfor\s([^\;]+)/i ) {
            $receivedfor = $1;
        }
    }

    for ( my $i = 0; $i <= $#RECPS; $i++ ) {
        if ( $RECPS[$i] =~ /\<(\S+)\>/ ) {
            $RECPS[$i] = $1;
        }
        elsif ( $RECPS[$i] =~ /\((\S+)\)/ ) {
            $RECPS[$i] = $1;
        }
    }

    my $matchdomain = 0;
    my $matchrecv   = 0;
    foreach my $ldomain (@LD) {
        $ldomain =~ s/\n//g;
        next if ( $ldomain !~ /\./ );
        foreach my $recp (@RECPS) {
            if ( $recp =~ /\@${ldomain}$/ ) {
                $matchdomain = 1;
            }
        }
        if ( $receivedfor =~ /\@${ldomain}$/ ) {
            $matchrecv = 1;
        }

    }

    if ( $receivedfor ne "" && $matchrecv == 0 && -e "/etc/webspam" ) {
        die "you are not permitted to relay mail";
    }

    if ($matchdomain) { return "yes"; }

    if ( -e "/etc/webspam" ) { die "you are not permitted to relay mail"; }

    return "yes";
}

sub checkuserpass {
    my ( $user, $pass, $shift ) = @_;
    my ($domain);
    my ( $owner, $homedir, $uid, $gid );
    if ( $user eq "" || ( $user eq $pass && length($shift) > 0 ) ) {    #netscape sucks!
        $user = $pass;
        $pass = $shift;
    }

    $user =~ s/[\+\%\/\:]/\@/g;

    my $trueowner;

    if ( $user =~ /\@/ ) {
        ( $user, $domain ) = split( /\@/, $user );
        if ( $domain eq "" ) {
            return "no";
        }
        $owner = getdomainowner($domain);
        if ( $owner eq "" ) {
            return "no";
        }
        $homedir = gethomedir($owner);
        if ( $homedir eq "" || $homedir eq "/" ) {
            return "no";
        }
        $owner =~ s/\\//g;
        ( undef, $uid, $gid ) = split( /:/, Exim::expand_string( '${lookup passwd{\N' . $owner . '\N}{$value}}' ) );
        $trueowner = $owner;
    }
    else {
        $user =~ s/\\//g;
        ( undef, $uid, $gid ) = split( /:/, Exim::expand_string( '${lookup passwd{\N' . $user . '\N}{$value}}' ) );
        $trueowner = $user;
    }

    $trueowner =~ s/\///g;
    $trueowner =~ s/\.\.//g;
    if ( isdemo( ${trueowner} ) ) {
        return ('no');
    }

    if ( checkpass( $user, $pass, $homedir, $domain ) ) {
        return "yes";
    }
    else {
        return "no";
    }
}

sub checkpass {
    my ( $user, $pass, $homedir, $domain ) = @_;
    my ($cpass);
    my ($retval) = 0;

    if ( $pass eq "" ) { return (0); }

    if ( -e "$homedir/etc/exim/authtab" ) {
        open( SHADOW, "$homedir/etc/exim/authtab" );
    }
    else {
        open( SHADOW, "$homedir/etc/${domain}/shadow" );
    }
    while (<SHADOW>) {
        if ( beginmatch( $_, "$user:" ) ) {
            ( $user, $cpass, undef ) = split( /:/, $_, 3 );
            if ( crypt( $pass, $cpass ) eq $cpass ) {
                close(SHADOW);
                return (1);
            }
            else {
                close(SHADOW);
                return (0);
            }
        }
    }
    close(SHADOW);

    return (0);
}

sub getdomainowner {
    my ($domain) = @_;
    my ($user)   = '';
    open( USERDOMS, "/etc/userdomains" );
    seek( USERDOMS, 0, 0 );
    while (<USERDOMS>) {
        s/\n//g;
        if ( beginmatch( $_, "$domain: " ) ) {
            /\S+:\s(\S+)/;
            $user = $1;
            last;
        }
    }
    close(USERDOMS);
    return $user;

}

sub gethomedir {
    my ($user) = @_;
    $user =~ s/\\//g;
    return ( Exim::expand_string( '${extract{5}{:}{${lookup passwd{\N' . $user . '\N}{$value}}}}' ) );
}

sub isdemo {
    my ($user) = @_;
    if ( $user =~ /^\d+$/ ) {
        $user = getpwuid($uid);
    }
    open( DEMOUSERS, "<", "/etc/demousers" );
    my @DEMOUSERS = <DEMOUSERS>;
    close(DEMOUSERS);

    if ( grep( /^\Q${user}\E$/, @DEMOUSERS ) ) {
        return (1);
    }

    return (0);
}

sub readbacktodate {
    my ($filename) = @_;
    my ($buf);
    my ($filepos) = 0;
    my $now = time();
    my $onehourago = ( $now - ( 60 * 60 ) );
    my ($hitcount) = 0;
    my ( $dev, $ino, $mode, $nlink, $uid, $gid, $rdev, $size, $atime, $mtime, $ctime, $blksize, $blocks ) = stat($filename);

    $filepos = ( $size - 4096 );
    open( RF, "$filename" );
    seek( RF, $filepos, 0 );
    my $reachedend = 0;

    while ( $filepos >= -4096 ) {
        if ( $filepos < 0 ) {
            read( RF, $buf, ( $filepos + 4096 ) );
        }
        else {
            read( RF, $buf, 4096 );
        }
        if ( $filepos > 0 ) {
            $buf =~ /([^\n]+\n)/;
            $filepos += length($1);
            $buf = substr( $buf, length($1) );
        }
        my @BUF = split( /\n/, $buf );
        foreach ( reverse @BUF ) {
            my ( $ttime, $tbytes ) = split(/ /);
            if ( $ttime > $onehourago ) {
                $hitcount++;
            }
            else {
                $reachedend = 1;
                last();
            }
        }

        last if ($reachedend);
        $filepos -= 4096;
        if ( $filepos < 0 ) {
            seek( RF, 0, 0 );
        }
        else {
            seek( RF, $filepos, 0 );
        }
    }

    close(RF);
    return ($hitcount);
}

sub beginmatch {
    my ( $haystack, $needle ) = @_;

    $haystack =~ tr/[A-Z]/[a-z]/;
    $needle   =~ tr/[A-Z]/[a-z]/;
    if ( substr( $haystack, 0, length($needle) ) eq $needle ) {
        return (1);
    }

    return (0);
}

sub setuids {
    my ($user) = $_[0];
    my ( $uid, $gid );
    $user =~ s/\\//g;
    ( undef, $uid, $gid ) = split( /:/, Exim::expand_string( '${lookup passwd{\N' . $user . '\N}{$value}}' ) );
    if ( !( $( = int($gid) ) ) {
        print "error setting gid\n";
        exit;
    }

    if ( !( $) = "$gid $gid" ) ) {
        print "error setting gid\n";
        exit;
    }
    if ( !( ( $< = $uid ) && ( $> = $uid ) ) ) {
        die "error setting uid ($uid) [$user]\n";
    }
    return $uid;
}

sub popbeforesmtpwarn {
    my ($hostaddress) = @_;
    if ( !-e "/etc/eximpopbeforesmtpwarning" ) {
        return ();
    }
    my (@SENDERS);
    open( RELAYHOSTS, "/etc/relayhostsusers" );
    while (<RELAYHOSTS>) {
        chomp();
        my ( $rhost, $user ) = split( / /, $_ );
        next if ( $rhost eq "" );
        if ( $hostaddress eq $rhost ) {
            push( @SENDERS, $user );
        }
    }
    close(RELAYHOSTS);
    if ( $#SENDERS > -1 ) {
        return ( "X-PopBeforeSMTPSenders: " . join( ",", @SENDERS ) );
    }
    return ("");
}

sub mailtrapheaders {
    my $primary_hostname      = Exim::expand_string('$primary_hostname');
    my $original_domain       = Exim::expand_string('$original_domain');
    my $sender_address_domain = Exim::expand_string('$sender_address_domain');
    my $originator_uid        = Exim::expand_string('$originator_uid');
    my $originator_gid        = Exim::expand_string('$originator_gid');
    my $caller_uid            = Exim::expand_string('$caller_uid');
    my $caller_gid            = Exim::expand_string('$caller_gid');
    my $xsource               = $ENV{'X-SOURCE'};
    my $xsourceargs           = $ENV{'X-SOURCE-ARGS'};
    my $xsourcedir            = maskdir( $ENV{'X-SOURCE-DIR'} );

    my $headers =
        "X-AntiAbuse: This header was added to track abuse, please include it with any abuse report\n"
      . "X-AntiAbuse: Primary Hostname - $primary_hostname\n"
      . "X-AntiAbuse: Original Domain - $original_domain\n"
      . "X-AntiAbuse: Originator/Caller UID/GID - [$originator_uid $originator_gid] / [$caller_uid $caller_gid]\n"
      . "X-AntiAbuse: Sender Address Domain - $sender_address_domain\n"
      . "X-Source: ${xsource}\n"
      . "X-Source-Args: ${xsourceargs}\n"
      . "X-Source-Dir: ${xsourcedir}";
    return ($headers);

}

sub maskdir {
    my ($dir) = @_;

    open( PASSWD, "/etc/passwd" );
    while (<PASSWD>) {
        my ( $homedir, $uid, $user );
        ( $user, undef, $uid, undef, undef, $homedir, undef ) =
          split( /:/, $_ );
        next if ( $uid < 100 );
        next if ( length($homedir) < 3 );
        if ( substr( $homedir, -1, 1 ) ne "/" ) { $homedir .= "/"; }

        if ( beginmatch( ${dir}, ${homedir} ) ) {
            my $maskeddir = $dir;
            $maskeddir =~ s/^${homedir}//g;
            $maskeddir = getusersdomain($user) . ":" . "/" . $maskeddir;

            close(PASSWD);
            return ($maskeddir);
        }
    }
    close(PASSWD);

    return ($dir);

}

sub getusersdomain {
    my ($user) = @_;
    open( USERDOMS, "/etc/trueuserdomains" );
    seek( USERDOMS, 0, 0 );
    while (<USERDOMS>) {
        s/\n//g;
        if ( endmatch( $_, " ${user}" ) ) {
            /(\S+):/;
            $domain = $1;
            last;
        }
    }
    close(USERDOMS);
    return $domain;
}

sub endmatch {
    my ( $haystack, $needle ) = @_;

    $haystack =~ tr/[A-Z]/[a-z]/;
    $needle   =~ tr/[A-Z]/[a-z]/;
    if ( substr( $haystack, -1 * length($needle) ) eq $needle ) {
        return (1);
    }

    return (0);
}

if ( -e "/usr/share/amavis/amavis-filter" && !-e "/etc/noamavis" ) {
    do '/usr/share/amavis/amavis-filter';
}

if ( -e "/etc/exim.pl.local" ) {
    do '/etc/exim.pl.local';
}
1;

# The following notice referes to code below this line:
#
# "THE BEER-WARE LICENSE" (Revision 42):
# <phk@login.dknet.dk> wrote this file.  As long as you retain this notice you
# can do whatever you want with this stuff. If we meet some day, and you think
# this stuff is worth it, you can buy me a beer in return.   Poul-Henning Kamp
#
# based on Crypt::PasswdMD5
#
# bdraco@darkorb.net              http://cpanel.net

sub checkpassword {
    my ( $password, $cryptedpassword ) = @_;
    if ( $cryptedpassword eq "" || $cryptedpassword =~ /^\!/ || $cryptedpassword =~ /^\*/ ) { return (0); }
    loadmd5();
    if ( $cryptedpassword =~ /^\$1\$(.+)\$.*/ && $hasmd5 ) {
        my $salt = getsalt($cryptedpassword);
        if ( unix_md5_crypt( $password, $salt ) eq $cryptedpassword ) {
            return (1);
        }
    }
    else {
        if ( crypt( $password, $cryptedpassword ) eq $cryptedpassword ) {
            return (1);
        }
    }
    return (0);
}

sub unix_md5_crypt {
    my $Magic = '$1$';    # Magic string

    my ( $pw, $salt ) = @_;
    my $passwd;

    $salt =~ s/^\Q$Magic//;    # Take care of the magic string if
                               # if present.

    $salt =~ s/^(.*)\$.*$/$1/; # Salt can have up to 8 chars...
    $salt = substr( $salt, 0, 8 );

    my $ctx = new Digest::Perl::MD5;    # Here we start the calculation
    $ctx->add($pw);                     # Original password...
    $ctx->add($Magic);                  # ...our magic string...
    $ctx->add($salt);                   # ...the salt...

    my ($final) = new Digest::Perl::MD5;
    $final->add($pw);
    $final->add($salt);
    $final->add($pw);
    $final = $final->digest;

    for ( my $pl = length($pw); $pl > 0; $pl -= 16 ) {
        $ctx->add( substr( $final, 0, $pl > 16 ? 16 : $pl ) );
    }

    # Now the 'weird' xform

    for ( my $i = length($pw); $i; $i >>= 1 ) {
        if ( $i & 1 ) { $ctx->add( pack( "C", 0 ) ); }

        # This comes from the original version,
        # where a memset() is done to $final
        # before this loop.
        else { $ctx->add( substr( $pw, 0, 1 ) ); }
    }

    $final = $ctx->digest;

    # The following is supposed to make
    # things run slower. In perl, perhaps
    # it'll be *really* slow!

    for ( my $i = 0; $i < 1000; $i++ ) {
        my $ctx1 = new Digest::Perl::MD5;
        if   ( $i & 1 ) { $ctx1->add($pw); }
        else            { $ctx1->add( substr( $final, 0, 16 ) ); }
        if ( $i % 3 ) { $ctx1->add($salt); }
        if ( $i % 7 ) { $ctx1->add($pw); }
        if ( $i & 1 ) { $ctx1->add( substr( $final, 0, 16 ) ); }
        else          { $ctx1->add($pw); }
        $final = $ctx1->digest;
    }

    # Final xform

    $passwd = '';
    $passwd .= to64( int( unpack( "C", ( substr( $final, 0, 1 ) ) ) << 16 ) | int( unpack( "C", ( substr( $final, 6,  1 ) ) ) << 8 ) | int( unpack( "C", ( substr( $final, 12, 1 ) ) ) ), 4 );
    $passwd .= to64( int( unpack( "C", ( substr( $final, 1, 1 ) ) ) << 16 ) | int( unpack( "C", ( substr( $final, 7,  1 ) ) ) << 8 ) | int( unpack( "C", ( substr( $final, 13, 1 ) ) ) ), 4 );
    $passwd .= to64( int( unpack( "C", ( substr( $inal,  2, 1 ) ) ) << 16 ) | int( unpack( "C", ( substr( $final, 8,  1 ) ) ) << 8 ) | int( unpack( "C", ( substr( $final, 14, 1 ) ) ) ), 4 );
    $passwd .= to64( int( unpack( "C", ( substr( $final, 3, 1 ) ) ) << 16 ) | int( unpack( "C", ( substr( $final, 9,  1 ) ) ) << 8 ) | int( unpack( "C", ( substr( $final, 15, 1 ) ) ) ), 4 );
    $passwd .= to64( int( unpack( "C", ( substr( $final, 4, 1 ) ) ) << 16 ) | int( unpack( "C", ( substr( $final, 10, 1 ) ) ) << 8 ) | int( unpack( "C", ( substr( $final, 5,  1 ) ) ) ), 4 );
    $passwd .= to64( int( unpack( "C", substr( $final, 11, 1 ) ) ), 2 );

    $final = '';
    return ( $Magic . $salt . '$' . $passwd );

}

sub to64 {
    my $itoa64 = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    my ( $v, $n ) = @_;
    my $ret = '';
    while ( --$n >= 0 ) {
        $ret .= substr( $itoa64, $v & 0x3f, 1 );
        $v >>= 6;
    }
    $ret;
}

sub getsalt {
    my ($cpass) = @_;
    ( $cpass =~ /^\$1\$(.+)\$.*/ ) and return $1;
    ( $cpass =~ /^(..)*/ )         and return $1;
}

sub democheck {
    my $uid = Exim::expand_string('$originator_uid');
    if ( isdemo($uid) ) { return 'yes'; }
    return 'no';
}

1;


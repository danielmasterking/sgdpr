# This script amends the PATH variable for interactive shells so
# that the composer command can be run.

#################################################################
## NOTE: If you want to disable this file, comment out or      ##
## delete the 'export' line below. If you simply delete the    ##
## file itself, then it will come back the next time that you  ##
## update cPanel & WHM.                                        ##
#################################################################

export PATH="$PATH:/opt/cpanel/composer/bin"

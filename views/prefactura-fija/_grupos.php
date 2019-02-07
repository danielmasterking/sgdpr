<ul class="list-group">

  <?php  foreach($grupos as $row): ?>
  <li class="list-group-item"><input type="checkbox" name="grupo[]" value="<?= $row->id ?>"> <?= $row->nombre ?></li>
<?php endforeach; ?>
  
</ul>

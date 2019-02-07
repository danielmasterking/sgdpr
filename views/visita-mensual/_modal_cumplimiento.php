<!-- Modal -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Observaciones</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?= Yii::$app->request->baseUrl.'/visita-mensual/cumplimiento'?>">

         <!-- VALIDACION -->
        <label>Cumplio</label>
        <label class="radio-inline">
          <input type="radio" name="cumple"  value="S" checked="" >Si
        </label>
        <label class="radio-inline">
          <input type="radio" name="cumple"  value="N" > No
        </label>

        <!-- ********** -->
        <textarea name="solucion" class="form-control" rows="5" required=""></textarea>
        <input type="hidden" name="tipo" id="tipo_cumplimiento">
        <input type="hidden" name="id"   id="id_cumplimiento">
        <input type="hidden" name="view" value="<?= $view?>">
        <input type="hidden" name="dependencia" value="<?= $dependencia?>">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function cumplimiento(id,tipo){
    $('#tipo_cumplimiento').val(tipo);
    $('#id_cumplimiento').val(id);

  }
</script>
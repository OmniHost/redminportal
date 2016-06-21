<div id="export-csv" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('admin/reports/orders') }}" accept-charset="UTF-8" report="form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ Lang::get('redminportal::buttons.close') }}</span></button>
                    <h4 class="modal-title">{{ Lang::get('redminportal::messages.export_to_excel') }}</h4>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="start_date">{{ Lang::get('redminportal::forms.start_date') }}</label>
                                <div class="input-group" id='start-date'>
                                    <input class="form-control datepicker" readonly="readonly" name="start_date" type="text" id="start_date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="end_date">{{ Lang::get('redminportal::forms.end_date') }}</label>
                                <div class="input-group" id='end-date'>
                                    <input class="form-control datepicker" readonly="readonly" name="end_date" type="text" id="end_date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <p class="help-block">{{ Lang::get('redminportal::messages.leave_all_blank_to_download_all') }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ Lang::get('redminportal::buttons.close') }}</button>
                    <input class="btn btn-primary" type="submit" value="{{ Lang::get('redminportal::buttons.download_excel') }}">
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
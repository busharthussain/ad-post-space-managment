<div class="modal-dialog messages-model">

    <!-- Modal content-->
    {!! Form::open(['id' => 'report-message-form', 'class' => 'form-horizontal']) !!}
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" style="visibility: hidden;">{{_lang('Modal Header')}}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="label-popup">{!! $title !!}</label>
                <textarea placeholder="{{_lang('Type Message Here')}}..." class="form-control" name="report_message" id="report_message" required></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-success" style="margin-top: 20px;">{{_lang('Send')}}</button>
            </div>
        </div>
    </div>
    <input type="hidden" name="report_id" id="report_id" value="{!! $reportId !!}">
    {!! Form::token() !!}
    {!! Form::close() !!}

</div>
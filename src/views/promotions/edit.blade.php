@extends('redminportal::layouts.master')

@section('content')
    @if($errors->has())
    <div class='alert alert-danger'>
        We encountered the following errors:
        <ul>
            @foreach($errors->all() as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{ Form::open(array('files' => TRUE, 'action' => 'Redooor\Redminportal\PromotionController@postStore', 'role' => 'form')) }}
        {{ Form::hidden('id', $promotion->id)}}

    	<div class='row'>
    	    <div class="col-md-3 col-md-push-9">
                <div class='form-actions text-right'>
                    {{ HTML::link('admin/promotions', 'Cancel', array('class' => 'btn btn-default'))}}
                    {{ Form::submit('Save Changes', array('class' => 'btn btn-primary')) }}
                </div>
                <hr>
                <div class='well well-sm'>
                    <div class="form-group">
                        <div class="checkbox">
                            <label for="active-checker">
                                {{ Form::checkbox('active', $promotion->active, $promotion->active, array('id' => 'active-checker')) }} Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('start_date', 'Start Date') }}
                    <div class="input-group" id='start-date'>
                        {{ Form::input('text', 'start_date', $start_date->format('d/m/Y'), array('class' => 'form-control datepicker', 'readonly')) }}
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('end_date', 'End Date') }}
                    <div class="input-group" id='end-date'>
                        {{ Form::input('text', 'end_date', $end_date->format('d/m/Y'), array('class' => 'form-control datepicker', 'readonly')) }}
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>
                    <div>
                        <span class="btn btn-default btn-file"><span class="fileupload-new">Upload photo</span><span class="fileupload-exists">Change</span>{{ Form::file('image') }}</span>
                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
                    </div>
                </div>
            </div>
    	    <div class="col-md-9 col-md-pull-3">
    	        <ul class="nav nav-tabs" id="lang-selector">
                   @foreach(\Config::get('redminportal::translation') as $translation)
                   <li><a href="#lang-{{ $translation['lang'] }}">{{ $translation['name'] }}</a></li>
                   @endforeach
               </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="lang-en">
                        <div class="form-group">
                            {{ Form::label('name', 'Title') }}
                            {{ Form::text('name', $promotion->name, array('class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('short_description', 'Summary') }}
                            {{ Form::text('short_description', $promotion->short_description, array('class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('long_description', 'Description') }}
                            {{ Form::textarea('long_description', $promotion->long_description, array('class' => 'form-control')) }}
                        </div>
                    </div>
                    @foreach(\Config::get('redminportal::translation') as $translation)
                        @if($translation['lang'] != 'en')
                        <div class="tab-pane" id="lang-{{ $translation['lang'] }}">
                            <div class="form-group">
                                {{ Form::label($translation['lang'] . '_name', 'Title') }}
                                @if ($translated)
                                {{ Form::text($translation['lang'] . '_name', (array_key_exists($translation['lang'], $translated) ? $translated[$translation['lang']]->name : ''), array('class' => 'form-control')) }}
                                @else
                                {{ Form::text($translation['lang'] . '_name', null, array('class' => 'form-control')) }}
                                @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label($translation['lang'] . '_short_description', 'Summary') }}
                                @if ($translated)
                                {{ Form::text($translation['lang'] . '_short_description', (array_key_exists($translation['lang'], $translated) ? $translated[$translation['lang']]->short_description : ''), array('class' => 'form-control')) }}
                                @else
                                {{ Form::text($translation['lang'] . '_short_description', null, array('class' => 'form-control')) }}
                                @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label($translation['lang'] . '_long_description', 'Description') }}
                                @if ($translated)
                                {{ Form::textarea($translation['lang'] . '_long_description', (array_key_exists($translation['lang'], $translated) ? $translated[$translation['lang']]->long_description : ''), array('class' => 'form-control')) }}
                                @else
                                {{ Form::textarea($translation['lang'] . '_long_description', null, array('class' => 'form-control')) }}
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                <h4>Uploaded Photos</h4>
                <div class='row'>
                    @foreach( $promotion->images as $image )
                    <div class='col-md-3'>
                        {{ HTML::image($imageUrl . $image->path, $promotion->name, array('class' => 'img-thumbnail', 'alt' => $image->path)) }}
                    </div>
                    @endforeach
                </div>
            </div>
    	</div>

        <hr>


    {{ Form::close() }}
@stop

@section('footer')
    <script src="{{ URL::to('packages/redooor/redminportal/assets/js/bootstrap-fileupload.js') }}"></script>
    <script>
        !function ($) {
            $(function(){
                $( ".datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });

                $( "#end-date .input-group-addon" ).click( function() {
                    $( "#end_date" ).datepicker( "show" );
                });

                $( "#start-date .input-group-addon" ).click( function() {
                    $( "#start_date" ).datepicker( "show" );
                });

                $('#lang-selector li').first().addClass('active');
                $('#lang-selector a').click(function (e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
            })
        }(window.jQuery);
    </script>
    @include('redminportal::plugins/tinymce')
@stop

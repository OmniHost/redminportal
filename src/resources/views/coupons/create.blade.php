@extends('redminportal::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin') }}">{{ Lang::get('redminportal::menus.home') }}</a></li>
                <li><a href="{{ URL::to('admin/coupons') }}">{{ Lang::get('redminportal::menus.coupons') }}</a></li>
                <li class="active">{{ Lang::get('redminportal::forms.create') }}</li>
            </ol>
        </div>
    </div>
    
    @include('redminportal::partials.errors')
    
    {!! Form::open(array('action' => '\Redooor\Redminportal\App\Http\Controllers\CouponController@postStore', 'role' => 'form', 'id' => 'form_add')) !!}
        <div class='row'>
	        <div class="col-md-9">
                <div class="form-horizontal">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ Lang::get('redminportal::forms.coupon_data') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.coupon_code') }}</label>
                                <div class="col-md-8">
                                    {!! Form::text('code', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.description') . ' (' . Lang::get('redminportal::forms.optional') . ')' }}</label>
                                <div class="col-md-8">
                                    {!! Form::textarea('description', null, array('class' => 'form-control', 'rows' => '3')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.amount') }}</label>
                                <div class="col-md-8">
                                    {!! Form::text('amount', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="is_percent" class="control-label col-md-4">{{ Lang::get('redminportal::forms.is_percent') }}</label>
                                <div class="col-md-8">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('is_percent', null, null, array('id' => 'is_percent', 'name' => 'is_percent')) !!}
                                            <i>{{ Lang::get('redminportal::forms.discount_explanation') }}</i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.start_date') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group date datepicker" id='start-date'>
                                        {!! Form::input('text', 'start_date', null, array('class' => 'form-control')) !!}
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.expiry_date') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group date datepicker" id='end-date'>
                                        {!! Form::input('text', 'end_date', null, array('class' => 'form-control')) !!}
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ Lang::get('redminportal::forms.usage_restrictions') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="multiple_coupons" class="control-label col-md-4">{{ Lang::get('redminportal::forms.multiple_coupons') }}</label>
                                <div class="col-md-8">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('multiple_coupons',  null,  null, array('id' => 'multiple_coupons')) !!}
                                            <i>{{ Lang::get('redminportal::forms.multiple_coupons_explanation') }}</i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="is_percent" class="control-label col-md-4">{{ Lang::get('redminportal::forms.exclude_sale_item') }}</label>
                                <div class="col-md-8">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('exclude_sale_item',  null,  null, array('id' => 'exclude_sale_item')) !!}
                                            <i>{{ Lang::get('redminportal::forms.exclude_sale_item_explanation') }}</i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.min_spent') }}</label>
                                <div class="col-md-8">
                                    {!! Form::text('min_spent', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.max_spent') }}</label>
                                <div class="col-md-8">
                                    {!! Form::text('max_spent', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.restricted_to') }}</label>
                                <div class="col-md-8">
                                    <h4>{{ Lang::get('redminportal::forms.categories') }}</h4>
                                    @if (count($categories) > 0)
                                    <p></p>
                                    {!! Form::select('category_id', $categories, null, array('class' => 'form-control', 'id' => 'category_id', 'multiple', 'name' => 'category_id[]')) !!}
                                    @else
                                    <div class="alert alert-warning">No category</div>
                                    @endif
                                    <h4>{{ Lang::get('redminportal::forms.products') }}</h4>
                                    @if (count($products) > 0)
                                    <p></p>
                                    {!! Form::select('product_id', $products, null, array('class' => 'form-control', 'id' => 'product_id', 'multiple', 'name' => 'product_id[]')) !!}
                                    @else
                                    <div class="alert alert-warning">No product</div>
                                    @endif
                                    <h4>{{ Lang::get('redminportal::forms.membership_modules') }}</h4>
                                    @if (count($membermodules) > 0)
                                    <p></p>
                                    {!! Form::select('pricelist_id', $membermodules, null, array('class' => 'form-control', 'id' => 'pricelist_id', 'multiple', 'name' => 'pricelist_id[]')) !!}
                                    @else
                                    <div class="alert alert-warning">No membership/module</div>
                                    @endif
                                    <p class="help-block">Coupon applied to product and membership/module will supersede category.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ Lang::get('redminportal::forms.usage_limit') }}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.usage_limit_per_coupon') }}</label>
                                <div class="col-md-8">
                                    {!! Form::text('usage_limit_per_coupon', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">{{ Lang::get('redminportal::forms.usage_limit_per_user') }}</label>
                                <div class="col-md-8">
                                    {!! Form::text('usage_limit_per_user', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
	        </div>
            <div class="col-md-3">
                <div class="well">
                    <div class='form-actions'>
                        {!! HTML::link('admin/coupons', Lang::get('redminportal::buttons.cancel'), array('class' => 'btn btn-link'))!!}
                        {!! Form::submit(Lang::get('redminportal::buttons.create'), array('class' => 'btn btn-primary pull-right')) !!}
                    </div>
                </div>
	        </div>
        </div>
    {!! Form::close() !!}
@stop

@section('footer')
    <script>
        !function ($) {
            $(function(){
                $( ".datepicker" ).datetimepicker({
                    format: 'DD/MM/YYYY hh:mm A',
                    showTodayButton: true
                });
            })
        }(window.jQuery);
    </script>
@stop

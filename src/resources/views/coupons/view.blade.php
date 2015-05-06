@extends('redminportal::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin') }}">{{ Lang::get('redminportal::menus.home') }}</a></li>
                <li class="active">{{ Lang::get('redminportal::menus.coupons') }}</li>
            </ol>
        </div>
    </div>
    
    @include('redminportal::partials.errors')

    <div class="row">
        <div class="col-md-12">
            <div class="nav-controls text-right">
                <div class="btn-group" role="group">
                @if (count($coupons) > 0)
                <a href="" class="btn btn-default btn-sm disabled btn-text">{{ $coupons->firstItem() . ' to ' . $coupons->lastItem() . ' of ' . $coupons->total() }}</a>
                @endif
                {!! HTML::link('admin/coupons/create', Lang::get('redminportal::buttons.create_new'), array('class' => 'btn btn-primary btn-sm')) !!}
            </div>
            </div>
        </div>
    </div>
    
    @if (count($coupons) > 0)
        <table class='table table-striped table-bordered table-condensed'>
            <thead>
                <tr>
                    <th>
                        <a href="{{ URL::to('admin/coupons/sort') . '/code/' . ($sortBy == 'code' && $orderBy == 'asc' ? 'desc' : 'asc') }}">
                            Coupon Code
                            @if ($sortBy == 'code')
                            {!! ($orderBy == 'asc' ? '<span class="caret"></span>' : '<span class="dropup"><span class="caret"></span></span>') !!}
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ URL::to('admin/coupons/sort') . '/amount/' . ($sortBy == 'amount' && $orderBy == 'asc' ? 'desc' : 'asc') }}">
                            Coupon Amount
                            @if ($sortBy == 'amount')
                            {!! ($orderBy == 'asc' ? '<span class="caret"></span>' : '<span class="dropup"><span class="caret"></span></span>') !!}
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ URL::to('admin/coupons/sort') . '/start_date/' . ($sortBy == 'start_date' && $orderBy == 'asc' ? 'desc' : 'asc') }}">
                            Coupon Start Date
                            @if ($sortBy == 'start_date')
                            {!! ($orderBy == 'asc' ? '<span class="caret"></span>' : '<span class="dropup"><span class="caret"></span></span>') !!}
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ URL::to('admin/coupons/sort') . '/end_date/' . ($sortBy == 'end_date' && $orderBy == 'asc' ? 'desc' : 'asc') }}">
                            Coupon Expiry Date
                            @if ($sortBy == 'end_date')
                            {!! ($orderBy == 'asc' ? '<span class="caret"></span>' : '<span class="dropup"><span class="caret"></span></span>') !!}
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ URL::to('admin/coupons/sort') . '/usage_limit_per_coupon/' . ($sortBy == 'usage_limit_per_coupon' && $orderBy == 'asc' ? 'desc' : 'asc') }}">
                            Usage Limit Per Coupon
                            @if ($sortBy == 'usage_limit_per_coupon')
                            {!! ($orderBy == 'asc' ? '<span class="caret"></span>' : '<span class="dropup"><span class="caret"></span></span>') !!}
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ URL::to('admin/coupons/sort') . '/usage_limit_per_user/' . ($sortBy == 'usage_limit_per_user' && $orderBy == 'asc' ? 'desc' : 'asc') }}">
                            Usage Limit Per User
                            @if ($sortBy == 'usage_limit_per_user')
                            {!! ($orderBy == 'asc' ? '<span class="caret"></span>' : '<span class="dropup"><span class="caret"></span></span>') !!}
                            @endif
                        </a>
                    </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($coupons as $coupon)
                    <tr>
                        <td>
                            {{ $coupon->code }}
                        </td>
                        <td>
                            {{ $coupon->amount }} @if($coupon->is_percent){{ "%" }}@else{{ "(fixed value)" }}@endif
                        </td>
                        <td>
                            {{ date("d/m/Y h:i A", strtotime($coupon->start_date)) }}
                        </td>
                        <td>
                            {{ date("d/m/Y h:i A", strtotime($coupon->end_date)) }}
                        </td>
                        <td>
                            {{ $coupon->usage_limit_per_coupon }}
                        </td>
                        <td>
                            {{ $coupon->usage_limit_per_user }}
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-cyan btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>
                                        <a href="{{ URL::to('admin/coupons/edit/' . $coupon->id) }}">
                                            <i class="glyphicon glyphicon-edit"></i>Edit</a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('admin/coupons/delete/' . $coupon->id) }}" class="btn-confirm">
                                            <i class="glyphicon glyphicon-remove"></i>Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">
        {!! $coupons->render() !!}
        </div>
    @else
        <div class="alert alert-info">No coupon found</div>
    @endif
@stop

@section('footer')
@stop

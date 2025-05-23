@extends('layouts.home_layout')

@section('section_stylesheet')
    @parent
    <!-- Bootstrap Toggle -->
    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap-toggle/css/bootstrap-toggle.min.css') }}" />
    <!-- Bootstrap Slider -->
    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap-slider/dist/css/bootstrap-slider.min.css') }}" />
@endsection

@section('section_script_main')
    @parent
@endsection

@section('content')
<!-- row -->
<div class="row">
    
    <!-- col -->
    <div class="col-sm-12">
        
        <!-- accordion -->
        <div class="panel-group" id="accordion">
            
            <!-- panel -->
            <div id="collapseOneParent" class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#collapseOneParent" href="#collapseOne"><span class="glyphicon glyphicon-plus"></span> Schedule for 3W Owners</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <!-- --- -->
                        <!-- row -->
                        <div class="row">

                            <!-- col -->
                            <div class="col-sm-12">
                                <!-- form -->
                                <form action="{!! route('notificationSchedule.storeScheduleTWOwner') !!}" method="POST" class="col-sm-8" autocomplete="off" id="form1" enctype="multipart/form-data">
                                    @csrf
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="is_active" class="col-sm-2 control-label">Feature</label>
                                        <div class="col-sm-10">
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="is_active" name="is_active" type="checkbox" data-toggle="toggle" {!! ($recurringTypeOwner->is_active)? 'checked' : null; !!}/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="is_recurring" class="col-sm-2 control-label">Recurrent</label>
                                        <div class="col-sm-10">
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="is_recurring" name="is_recurring" type="checkbox" data-toggle="toggle" {!! ($recurringPatternOwner->is_recurring)? 'checked' : null; !!}/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="day" class="col-sm-2 control-label" title="Day">Day</label>
                                        <div class="col-sm-10">
                                            @php
                                                $dayInputRange = range(0,7,1);
                                                $dayInputRange = implode(" ,", $dayInputRange);
                                            @endphp
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="day" name="day" value="{!! $recurringPatternOwner->day !!}" data-provide="slider" data-slider-ticks="[{!! $dayInputRange !!}]" data-slider-ticks-labels='[{!! $dayInputRange !!}]' data-slider-min="0" data-slider-max="7" data-slider-step="1" data-slider-value="{!! $recurringPatternOwner->day !!}" data-slider-tooltip="hide" style="width: 100%;"/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="day_of_week" class="col-sm-2 control-label" title="Day Of Week">DOW (1 - 7)(7=Sunday)</label>
                                        <div class="col-sm-10">
                                            @php
                                                $dowInputRange = range(0,7,1);
                                                $dowInputRange = implode(" ,", $dowInputRange);
                                            @endphp
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="day_of_week" name="day_of_week" value="{!! $recurringPatternOwner->day_of_week !!}" data-provide="slider" data-slider-ticks="[{!! $dowInputRange !!}]" data-slider-ticks-labels='[{!! $dowInputRange !!}]' data-slider-min="0" data-slider-max="24" data-slider-step="1" data-slider-value="{!! $recurringPatternOwner->day_of_week !!}" data-slider-tooltip="hide" style="width: 100%;"/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="month" class="col-sm-2 control-label" title="Month">M (1 - 12)</label>
                                        <div class="col-sm-10">
                                            @php
                                                $monthInputRange = range(0,12,1);
                                                $monthInputRange = implode(" ,", $monthInputRange);
                                            @endphp
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="month" name="month" value="{!! $recurringPatternOwner->month !!}" data-provide="slider" data-slider-ticks="[{!! $monthInputRange !!}]" data-slider-ticks-labels='[{!! $monthInputRange !!}]' data-slider-min="0" data-slider-max="24" data-slider-step="1" data-slider-value="{!! $recurringPatternOwner->month !!}" data-slider-tooltip="hide" style="width: 100%;"/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->

                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <!-- btn-toolbar -->
                                        <div class="col col-sm-12">
                                            <!-- div class="btn-group btn-group-lg pull-right" -->
                                                <button type="submit" class="btn btn-primary pull-right" id="submit">Submit</button>
                                            <!-- /div -->
                                        </div>
                                    </div>
                                    <!-- /.form-group -->

                                </form>
                                <!-- /.form -->
                            </div>
                            <!-- /.col -->

                        </div>
                        <!-- /.row -->
                        <!-- --- -->
                    </div>
                </div>
            </div>
            <!-- /.panel -->
            
            <!-- panel -->
            <div id="collapseTwoParent" class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#collapseTwoParent" href="#collapseTwo"><span class="glyphicon glyphicon-plus"></span> Schedule for HOD</a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <!-- --- -->
                        <!-- row -->
                        <div class="row">

                            <!-- col -->
                            <div class="col-sm-12">
                                <!-- form -->
                                <form action="{!! route('notificationSchedule.storeScheduleHOD') !!}" method="POST" class="col-sm-8" autocomplete="off" id="form2" enctype="multipart/form-data">
                                    @csrf
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="is_active" class="col-sm-2 control-label">Feature</label>
                                        <div class="col-sm-10">
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="is_active" name="is_active" type="checkbox" data-toggle="toggle" {!! ($recurringTypeHOD->is_active)? 'checked' : null; !!}/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="is_recurring" class="col-sm-2 control-label">Recurrent</label>
                                        <div class="col-sm-10">
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="is_recurring" name="is_recurring" type="checkbox" data-toggle="toggle" {!! ($recurringPatternHOD->is_recurring)? 'checked' : null; !!}/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="day" class="col-sm-2 control-label" title="Day">Day</label>
                                        <div class="col-sm-10">
                                            @php
                                                $dayInputRange = range(0,7,1);
                                                $dayInputRange = implode(" ,", $dayInputRange);
                                            @endphp
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="day" name="day" value="{!! $recurringPatternHOD->day !!}" data-provide="slider" data-slider-ticks="[{!! $dayInputRange !!}]" data-slider-ticks-labels='[{!! $dayInputRange !!}]' data-slider-min="0" data-slider-max="7" data-slider-step="1" data-slider-value="{!! $recurringPatternHOD->day !!}" data-slider-tooltip="hide" style="width: 100%;"/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="day_of_week" class="col-sm-2 control-label" title="Day Of Week">DOW (1 - 7)(7=Sunday)</label>
                                        <div class="col-sm-10">
                                            @php
                                                $dowInputRange = range(0,7,1);
                                                $dowInputRange = implode(" ,", $dowInputRange);
                                            @endphp
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="day_of_week" name="day_of_week" value="{!! $recurringPatternHOD->day_of_week !!}" data-provide="slider" data-slider-ticks="[{!! $dowInputRange !!}]" data-slider-ticks-labels='[{!! $dowInputRange !!}]' data-slider-min="0" data-slider-max="24" data-slider-step="1" data-slider-value="{!! $recurringPatternHOD->day_of_week !!}" data-slider-tooltip="hide" style="width: 100%;"/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->
                                    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <label for="month" class="col-sm-2 control-label" title="Month">M (1 - 12)</label>
                                        <div class="col-sm-10">
                                            @php
                                                $monthInputRange = range(0,12,1);
                                                $monthInputRange = implode(" ,", $monthInputRange);
                                            @endphp
                                            <!-- p class="form-control-static"></p -->
                                            <input class="form-control" id="month" name="month" value="{!! $recurringPatternHOD->month !!}" data-provide="slider" data-slider-ticks="[{!! $monthInputRange !!}]" data-slider-ticks-labels='[{!! $monthInputRange !!}]' data-slider-min="0" data-slider-max="24" data-slider-step="1" data-slider-value="{!! $recurringPatternHOD->month !!}" data-slider-tooltip="hide" style="width: 100%;"/>
                                        </div>
                                        <!-- span id="form-control" class="help-block"></span -->
                                    </div>
                                    <!-- /.form-group -->

                                    <!-- form-group -->
                                    <div class="form-group col-sm-12">
                                        <!-- btn-toolbar -->
                                        <div class="col col-sm-12">
                                            <!-- div class="btn-group btn-group-lg pull-right" -->
                                                <button type="submit" class="btn btn-primary pull-right" id="submit">Submit</button>
                                            <!-- /div -->
                                        </div>
                                    </div>
                                    <!-- /.form-group -->

                                </form>
                                <!-- /.form -->
                            </div>
                            <!-- /.col -->

                        </div>
                        <!-- /.row -->
                        <!-- --- -->
                    </div>
                </div>
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.accordion -->
        
    </div>
    <!-- /.col -->
    
</div>
<!-- /.row -->
@endsection

@section('section_script')
    @parent
    <!-- Bootstrap Toggle -->
    <script src="{{ asset('node_modules/bootstrap-toggle/js/bootstrap-toggle.min.js') }}"></script>
    <!-- Bootstrap Slider -->
    <script src="{{ asset('node_modules/bootstrap-slider/dist/bootstrap-slider.min.js') }}"></script>
    <script>
    $(function() {
        "use strict";
    });
    </script>
@endsection
@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '運動會報名')

@section('content')
    <?php
    $active['admin'] ="";
    $active['show'] ="";    
    $active['list'] ="";
    $active['score'] ="active";
    $active['teacher'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>運動會報名-成績處理</h1>
            @include('sport_meetings.nav')
            <hr>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sport_meeting.score') }}">1.自訂獎狀</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('sport_meeting.score_input') }}">2.成績登錄</a>
                  </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.all_scores') }}">3.成績一覽表</a>                  
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('sport_meeting.total_scores') }}">4.田徑賽計分總表</a>
                </li>                
              </ul>    
              <hr>
              <h2>田徑賽計分總表</h2>
              <form name="myform">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {{ Form::select('select_action', $action_array, $select_action, ['class' => 'form-control','onchange'=>'jump()']) }}
                    </div>
                </div>
            </form>
            <a href="{{ route('sport_meeting.total_scores_print',$select_action) }}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> 下載 docx 檔</a>
            <table class="table table-striped table-bordered">
                <thead class="table-primary">
                <tr>
                    <th rowspan="2">
                        項目
                    </th>
                    <th colspan="{{ count($sex_item['男']) }}">
                        男生
                    </th>
                    <th colspan="{{ count($sex_item['女']) }}">
                        女生
                    </th>
                    @foreach($sex_item['不'] as $k=>$v)
                        <th rowspan="2">
                            {{ $v }}
                        </th>
                    @endforeach
                    <th rowspan="2">
                        總計
                    </th>
                    <th rowspan="2">
                        名次
                    </th>
                </tr>
                <tr>
                    @foreach($sex_item['男'] as $k=>$v)
                        <th>{{ $v }}</th>
                    @endforeach
                    @foreach($sex_item['女'] as $k=>$v)
                        <th>{{ $v }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>                
                @foreach($class_data as $k=>$v)
                <?php $row[$v]=null; ?>
                    <tr>
                        <td>
                            <?php
                                if(!isset($show_class[substr($v,0,1)])) $show_class[substr($v,0,1)] = "";
                                $show_class[substr($v,0,1)] = $show_class[substr($v,0,1)]."\"".$v."\",";
                            ?>
                            {{ $v }}
                        </td>
                        @foreach($sex_item['男'] as $k1=>$v1)
                            <td>
                                <?php $one = null; ?>
                                @if(isset($item_ranking[$v][$k1]['男']))                                    
                                    @foreach($item_ranking[$v][$k1]['男'] as $k2=>$v2)
                                        <?php
                                        if(isset($scoring[$k1][$v2])){
                                            $one = (int)$one+(int)$scoring[$k1][$v2];
                                        }                                            
                                        ?>                                        
                                    @endforeach
                                @endif    
                                {{ $one }}
                                <?php $row[$v] = (int)$row[$v]+(int)$one; ?>
                            </td>
                        @endforeach
                        @foreach($sex_item['女'] as $k1=>$v1)
                            <td>
                                <?php $one = null; ?>
                                @if(isset($item_ranking[$v][$k1]['女']))                                    
                                    @foreach($item_ranking[$v][$k1]['女'] as $k2=>$v2)
                                        <?php
                                        if(isset($scoring[$k1][$v2])){
                                            $one = (int)$one+(int)$scoring[$k1][$v2];
                                        }                                            
                                        ?>                                        
                                    @endforeach
                                @endif    
                                {{ $one }}
                                <?php $row[$v]= (int)$row[$v]+(int)$one; ?>
                            </td>
                        @endforeach
                        @foreach($sex_item['不'] as $k1=>$v1)
                            <td>
                                <?php $one = null; ?>
                                @if(isset($item_ranking[$v][$k1]['不']))                                    
                                    @foreach($item_ranking[$v][$k1]['不'] as $k2=>$v2)
                                        <?php
                                        if(isset($scoring[$k1][$v2])){
                                            $one = (int)$one+(int)$scoring[$k1][$v2];
                                        }                                            
                                        ?>                                        
                                    @endforeach
                                @endif    
                                {{ $one }}
                                <?php $row[$v]= (int)$row[$v]+(int)$one; ?>
                            </td>
                        @endforeach
                        <td>
                            @if($row[$v] <> 0)
                                {{ $row[$v] }}
                            @endif                            
                        </td>
                        <td>
                            {{ $year_award[$v] }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>    
    <?php        
        foreach($show_class as $k => $v){
            $show_class[$k] = substr($v,0,-1);
        }
    ?>
    <script>
      function jump(){
          if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
              location="/sport_meeting/total_scores/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
          }
      }            
  </script>
@endsection

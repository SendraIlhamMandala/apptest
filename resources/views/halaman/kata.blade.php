@extends('dashboard.base')

@section('content')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
 
<div class="container-fluid">

    <!-- /.row-->
    <div class="row">
        <div class="col-8">
            <div class=" h-75 card">

                <div class="card-body">

                    <form class="form-horizontal" method="post" action="/halaman" enctype="multipart/form-data">
                        {{ csrf_field() }}


                        <div class="controls">
                            <div class="input-group">
                                <input class="form-control" placeholder="input text" name="inputKata"
                                    id="appendedInputButton" size="16" type="text"><span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">Go!</button></span>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class=" h-75 card">

                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="file" class="form-control-file" id="exampleFormControlFile1">
                            </div>
                            <div class="col-lg-6">
                                <button class="btn btn-secondary" type="submit">Submit form</button>
                            </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- /.col-->
    </div>
    <!-- /.row-->


    <div class="fade-in  col-12">
        <div class="row  ">
            
            <div class="col-12 ">
                        @isset($data_kata)
                        <div class="card card card-accent-info">
                            <div class="card-header h4 ">Output</div>
                            <div class="card-body">
                                    
                                @foreach($data_kata as $datakey => $data)

                                    {{-- <div class=" row card-header " > --}}
                                    <div class=" row ">
                                        <div class=" col-6 col-md-4 border-bottom " style="height: 2rem;" >

                                            {{ $datakey }}
                                        </div>
                                        <div class=" col-6 col-md-8 border-bottom ">
                                            : {{ $data }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endisset

                    @isset($teks)
                        
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 ">
                                <div class="card">
                                    <div class="card-header h4 ">Conto Teks </div>
                                    <div class="card-body h5">
                                        {{ $teks }}
                                    </div>
                                </div>
                            </div>     
                           
        
                        </div>
                    </div>   
                    @endisset

                <div class="col-12 mb-5">
                    <div class="row">
                        <div class="col-12 ">
                            <button class="btn btn-block btn-secondary" type="button" aria-pressed="true" onClick="window.open('{{ URL::to('/halaman')}}','_self');">close</button>
                        </div>
                 
                    </div>
                </div>


        
{{-- 
            <div class="col-12">
                <div class="row">
                    @foreach ($data_kata as $datakatakey => $datakata )
                    <div class="col-4 ">
                        <div class="card">
                            <div class="card-header h4 ">{{ $datakatakey }} </div>
                            <div class="card-body">
                                {{ $datakata }}
                            </div>
                        </div>
                    </div>    
                    @endforeach
                    
                   

                </div>
            </div> --}}
            <div class="col-12 mb-4">
                <!-- /.row-->
                <div class="row">
                    <div class=" col-md-8 col-sm-12 ">
                        <div class="card h-100">
                            <div class="card-header h4">Afiksasi
                                <div class="card-header-actions"><a class="card-header-action"
                                        href="http://www.chartjs.org" target="_blank"><small
                                            class="text-muted">docs</small></a></div>
                            </div>
                            <div class="card-body">
                                <div class="c-chart-wrapper">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 m-md-0 m-sm-auto  ">
                        <div class="card h-100 ">
                            <div class="card-header h4">Stemming
                                <div class="card-header-actions"><a class="card-header-action"
                                        href="http://www.chartjs.org" target="_blank"><small
                                            class="text-muted">docs</small></a></div>
                            </div>
                            <div class="row">
                                <div class="card-body m-auto m-lg-0 col-6 col-md-12 ">
                                    <div class="c-chart-wrapper">
                                        <canvas id="myChart2"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-->
                </div>
                <!-- /.row-->

            </div>

            <div class="col-12">
                <!-- /.row-->
                <div class="row">
                    <div class=" col-12  ">
                        <div class="card h-100">
                            <div class="card-header h4">Kata
                                <div class="card-header-actions"><a class="card-header-action"
                                        href="http://www.chartjs.org" target="_blank"><small
                                            class="text-muted">docs</small></a></div>
                            </div>
                            <div class="card-body">
                                <div class="c-chart-wrapper">
                                    <table id="emptableid" class="table table-striped"  width="100%">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Kecap</th>
                                                <th>action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                         

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.col-->
                </div>
                <!-- /.row-->

            </div>

        </div>
    </div>



</div>



@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('myChart');
    var ctx2 = document.getElementById('myChart2');

    var arr = ["{{ $data_kata['prefiks'] ?? '' }} "],
        arr1 = ["{{ $data_kata['infiks'] ?? '' }}"],
        arr2 = ["{{ $data_kata['sufiks'] ?? '' }}"];
        // arr3 = [4];

    // function random1sampai8(x) {
    //     var r = Math.floor(Math.random() * 100) + 1;
    //     if (x.indexOf(r) === -1) x.push(r);
    // }

    // while (arr.length < 8) {
    //     random1sampai8(arr)
    //     random1sampai8(arr1)
    //     random1sampai8(arr2)
    //     random1sampai8(arr3)
    // }

    const config1 = {
        type: 'bar',
        data: {
            labels: [''],
            datasets: [{
                axis: 'y',
                label: 'Prefiks',
                data: arr,
                backgroundColor: 'rgba(114, 114, 114, 1)',

                borderWidth: 1
            }, {
                axis: 'y',
                label: 'Infiks',
                data: arr1,
                backgroundColor: 'rgba(151, 151, 151, 1)',
                borderWidth: 1
            }, {
                axis: 'y',
                label: 'Sufiks',
                data: arr2,
                backgroundColor: 'rgba(179, 179, 179, 1)',
                borderWidth: 1
            }, 
            // {
            //     axis: 'y',
            //     label: 'Confiks',
            //     data: arr3,
            //     backgroundColor: 'rgba(198, 198, 198, 1)',
            //     borderWidth: 1
            // },
             ]
        },
        options: {
            indexAxis: 'y',
        }
    };

    const config2 = {
        type: 'doughnut',
        data: {
            labels: [
                'Suskes',
                'Under',
                'Over'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [300, 50, 100],
                backgroundColor: [
                    'rgba(114, 114, 114, 1)',
                    'rgba(151, 151, 151, 1)',
                    'rgba(179, 179, 179, 1)'
                ],
                hoverOffset: 4
            }]
        },
    };

    var myChart = new Chart(ctx, config1);
    var myChart2 = new Chart(ctx2, config2);

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    
    $(document).ready(function() {
 
      $("#emptableid").DataTable({
              serverSide: true,
              ajax: {
                  url: '{{url('listtabelkata')}}',
               
              },
              buttons: false,
              searching: true,
            //   scrollY: 500,
              scrollY:        '50vh',
              scrollX: true,
              scrollCollapse: true,
              columns: [
                  {data: "id_kecap", className: 'id_kecap'},
                  {data: "kecap", className: 'kecap'},                
                  {data: "action", className: 'ints'},                
               
              ]  
        });
 
    });
 
  </script>

@endsection

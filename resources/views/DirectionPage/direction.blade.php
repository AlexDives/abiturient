@extends('layout.layout-2')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('scripts')
    <!-- Dependencies -->
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables/datatables.js') }}"></script>
    <script>
        function change_branch()
        {
            var abit_branch = $("#abit_branch").val();
            if (abit_branch != -1)
            {
                $.ajax({
                    url: '/direction/get_facultet',
                    type: 'POST',
                async: true,
                    data: {
                        abit_branch: abit_branch
                    },
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#abit_facultet').html(data);
                        $('#abit_facultet').prop('disabled', false);
                    },
                    error: function(msg) {
                        alert('Error, try again');
                    }
                });
            }
            else
            {
                $('#abit_facultet').prop('disabled', 'disabled');
                $('#abit_group_name').prop('disabled', 'disabled');
                $('#abit_fo_id').prop('disabled', 'disabled');
                $('#abit_shifr').prop('disabled', 'disabled');
                $('#abit_nick').prop('disabled', 'disabled');
                $('#abit_oku').prop('disabled', 'disabled');
            }
        }

        function change_facultet()
        {
            var abit_facultet = $('#abit_facultet').val();
            if (abit_facultet != -1)
            {
                $('#abit_group_name').prop('disabled', false);
                $('#abit_fo_id').prop('disabled', false);
                $('#abit_shifr').prop('disabled', false);
                $('#abit_nick').prop('disabled', false);
                $('#abit_oku').prop('disabled', false);
                change_stlevel();
            }
            else
            {
                $('#abit_group_name').prop('disabled', 'disabled');
                $('#abit_fo_id').prop('disabled', 'disabled');
                $('#abit_shifr').prop('disabled', 'disabled');
                $('#abit_nick').prop('disabled', 'disabled');
                $('#abit_oku').prop('disabled', 'disabled');
            }
        }

        function change_stlevel()
        {
            var id = $('#abit_oku').val();
            $.ajax({
                url: '/direction/get_predmet',
                type: 'POST',
                data: {
                    stid : id
                },
                async: true,
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#table_predmet').html(data);
                },
                error: function(msg) {
                    alert('Error, try again');
                }
            });
        }

        function search_pred()
        {
            var text = $('#search_predmet').val();
            var id = $('#abit_oku').val();
            $.ajax({
                url: '/direction/search_predmet',
                type: 'POST',
                data: {
                    stid : id,
                    text : text
                },
                async: true,
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#table_predmet').html(data);
                },
                error: function(msg) {
                    alert('Error, try again');
                }
            });
        }

        function selected_group(data)
        {
            $('#abit_facultet option[value="' + data[0].fk_id +'"]').prop('selected', true);
            $('#abit_fo_id option[value="' + data[0].fo_id +'"]').prop('selected', true);
            $('#abit_oku option[value="' + data[0].st_id +'"]').prop('selected', true);
            change_stlevel();
            $('#abit_group_name').val(data[0].name);
            $('#abit_nick').val(data[0].nick);
            $('#abit_shifr').val(data[0].minid);

            $('#abit_facultet').prop('disabled', false);
            $('#abit_group_name').prop('disabled', false);
            $('#abit_fo_id').prop('disabled', false);
            $('#abit_shifr').prop('disabled', false);
            $('#abit_nick').prop('disabled', false);
            $('#abit_oku').prop('disabled', false);
        }

        function select_group(id, branch_id)
        {
            $('#agid').val(id);
            $('#abit_branch option[value="' + branch_id +'"]').prop('selected', true);
            change_branch()
            $.ajax({
                url: '/direction/get_group',
                type: 'POST',
                data: {
                    gid : id
                },
                async: true,
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    selected_group(data);
                },
                error: function(msg) {
                    alert('Error, try again');
                }
            });
        }
    </script>
@endsection

@section('content')
    <h4 class="font-weight-bold py-3 mb-4">
         Направление
    </h4>
    <div class="card mb-4">
        <h6 class="card-header">
            Создать новое направление
        </h6>
        <div class="card-body">
            <form action="/direction/save" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="agid" id="agid" value="-1">
                <div class="form-group">
                    <label class="form-label">Выбор структуры
                        <span class="text-danger">*</span>
                    </label>
                    <select onchange="change_branch();" class="form-control" data-style="btn-default" data-icon-base="ion" data-tick-icon="ion-md-checkmark" name="abit_branch" id="abit_branch">
                        <option value="-1">Выберите элемент</option>
                        @foreach ($abit_branch as $ab)
                            <option value="{{ $ab->id }}">{{ $ab->short_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row" id="abit_facultet_block">
                    <div class="form-group col-md-6">
                        <label class="form-label">Институт/Факультет
                            <span class="text-danger">*</span>
                        </label>
                        <select onchange="change_facultet();" class="form-control" data-style="btn-default" data-icon-base="ion" data-tick-icon="ion-md-checkmark" name="abit_facultet" id="abit_facultet" disabled>
                            <option value="-1">Выберите элемент</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-label">Форма обучения
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" data-style="btn-default" data-icon-base="ion" data-tick-icon="ion-md-checkmark" name="abit_fo_id" id="abit_fo_id" disabled>
                            @foreach ($form_obuch as $fo)
                                <option value="{{ $fo->id }}">{{ $fo->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-label">Образовательный уровень
                            <span class="text-danger">*</span>
                        </label>
                        <select onchange="change_stlevel();" class="form-control" data-style="btn-default" data-icon-base="ion" data-tick-icon="ion-md-checkmark" name="abit_oku" id="abit_oku" disabled>
                            @foreach ($stlevel as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                      <label class="form-label">Направление
                          <span class="text-danger">*</span>
                      </label>
                      <input type="text" class="form-control" name="abit_group_name" id="abit_group_name" disabled>
                  </div>
                <div class="form-group col-md-3">
                    <label class="form-label">Ник
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" name="abit_nick" id="abit_nick" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label class="form-label">Шифр направления
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" name="abit_shifr" id="abit_shifr" disabled>
                </div>
                </div>
                <div class="form-group form-group-predmet">
                    <div class="form-label-sticky">
                        <!--<label class="form-label">
                            <span>Выбрать предметы</span>
                            <span>
                                <input type="text" name="search_predmet" id="search_predmet" onkeyup="search_pred();">
                            </span>
                        </label>-->
                        <label class="form-label">
                            <span>№</span>
                            <span>Наименование</span>
                            <span style="justify-content:flex-end; margin-right:25px;">Образовательный уровень</span>
                        </label>
                    </div>
                    <table class="table table-hover" style="cursor: default;">
                        <tbody id="table_predmet">
                        </tbody>
                    </table>
                </div>
                <input type="submit" class="btn btn-primary" value="Сохранить">
            </form>
        </div>

    </div>
    <div class="form-group">
        <label class="form-label">Список направлений</label>
        <table class="table table-hover" style="cursor: default;">
            <thead>
                <tr>
                    <th class="text-center">№</th>
                    <th class="text-left">Структура</th>
                    <th class="text-left">Институт / факультет</th>
                    <th class="text-left">Форма обучения</th>
                    <th class="text-left">Образовательный уровень</th>
                    <th class="text-left">Мин. код</th>
                    <th class="text-left">Направление</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($abit_group as $ag)
                    <tr onclick="select_group({{ $ag->id.','.$ag->branch_id }});">
                        <td class="text-center"><span class="custom-control-label">{{ $ag->id }}</span></td>
                        <td class="text-left">{{ $ag->branch_name }}</td>
                        <td class="text-left">{{ $ag->facult_name }}</td>
                        <td class="text-left">{{ $ag->form_obuch }}</td>
                        <td class="text-left">{{ $ag->stlevel_name }}</td>
                        <td class="text-left">{{ $ag->minid }}</td>
                        <td class="text-left">{{ $ag->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

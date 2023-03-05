@extends('admin.layouts.master')

@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Roles') }}</h1>
            {{ Breadcrumbs::render('role/view') }}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('admin.role.save-permission', $role) }}" method="POST">
                            @csrf
                            <div class="card-header">
                                    <h3>
                                        {{ __('files.Permission') }}
                                        -
                                        <span style="color:  #fc544b">( {{ $role->name }} )</span>
                                    </h3>
                            </div>

                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('files.Role') }}</th>
                                        <th>{{ __('files.Role_Create') }}</th>
                                        <th>{{ __('files.Role_Edit') }}</th>
                                        <th>{{ __('files.Role_Delete') }}</th>
                                        <th>{{ __('files.Role_Show') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (count($permissionList)) {
                                    foreach ($permissionList as $permission) { ?>
                                    <tr>
                                        <td data-title="{{ __('#') }}">
                                            <input type="checkbox" id="<?=$permission->name?>" style="width: 15px ; height: 15px;"
                                                   name="<?=$permission->name?>" value="<?=$permission->id?>"
                                                   <?= isset($permissions[$permission->id]) ? 'checked' : '' ?> onclick="processCheck(this);"
                                                   class="mainmodule"/>
                                        </td>
                                        <td data-title="{{ __('Module Name') }}">
                                                <?= ucfirst($permission->name) ?>
                                        </td>
                                        <td data-title="{{ __('Create') }}">
                                                <?php
                                                $permissionCreate = $permission->name . '_create';
                                            if (isset($permissionArray[$permissionCreate])) { ?>
                                            <input type="checkbox" id="<?=$permissionCreate?>"  style="width: 15px ; height: 15px;"
                                                   name="<?=$permissionCreate?>"
                                                   value="<?=$permissionArray[$permissionCreate]?>" <?= isset($permissions[$permissionArray[$permissionCreate]]) ? 'checked' : '' ?> />
                                            <?php } else {
                                                echo "&nbsp;";
                                            } ?>
                                        </td>
                                        <td data-title="{{ __('Edit') }}">
                                                <?php
                                                $permissionEdit = $permission->name . '_edit';
                                            if (isset($permissionArray[$permissionEdit])) { ?>
                                            <input type="checkbox" id="<?=$permissionEdit?>" name="<?=$permissionEdit?>" style="width: 15px ; height: 15px;"
                                                   value="<?=$permissionArray[$permissionEdit]?>" <?= isset($permissions[$permissionArray[$permissionEdit]]) ? 'checked' : '' ?> />
                                            <?php } else {
                                                echo "&nbsp;";
                                            } ?>
                                        </td>
                                        <td data-title="{{ __('Delete') }}">
                                                <?php
                                                $permissionDelete = $permission->name . '_delete';
                                            if (isset($permissionArray[$permissionDelete])) { ?>
                                            <input type="checkbox" id="<?=$permissionDelete?>" style="width: 15px ; height: 15px;"
                                                   name="<?=$permissionDelete?>"
                                                   value="<?=$permissionArray[$permissionDelete]?>" <?= isset($permissions[$permissionArray[$permissionDelete]]) ? 'checked' : '' ?> />
                                            <?php } else {
                                                echo "&nbsp;";
                                            } ?>
                                        </td>
                                        <td data-title="{{ __('Show') }}">
                                                <?php
                                                $permissionShow = $permission->name . '_show';
                                            if (isset($permissionArray[$permissionShow])) { ?>
                                            <input type="checkbox" id="<?=$permissionShow?>" name="<?=$permissionShow?>" style="width: 15px ; height: 15px;"
                                                   value="<?=$permissionArray[$permissionShow]?>" <?= isset($permissions[$permissionArray[$permissionShow]]) ? 'checked' : '' ?> />
                                            <?php } else {
                                                echo "&nbsp;";
                                            } ?>
                                        </td>
                                    </tr>
                                    <?php }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer">
                                <button class="btn btn-primary mr-1" type="submit">{{ __('files.Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        $('.mainmodule').each(function () {
            var mainmodule = $(this).attr('id');

            var mainidCreate = mainmodule + "_create";
            var mainidEdit = mainmodule + "_edit";
            var mainidDelete = mainmodule + "_delete";
            var mainidShow = mainmodule + "_show";

            if (!$('#' + mainmodule).is(':checked')) {
                $('#' + mainidCreate).prop('disabled', true);
                $('#' + mainidCreate).prop('checked', false);

                $('#' + mainidEdit).prop('disabled', true);
                $('#' + mainidEdit).prop('checked', false);

                $('#' + mainidDelete).prop('disabled', true);
                $('#' + mainidDelete).prop('checked', false);

                $('#' + mainidShow).prop('disabled', true);
                $('#' + mainidShow).prop('checked', false);
            }
        });

        function processCheck(event) {
            var mainmodule = $(event).attr('id');
            // console.log(mainmodule);

            // console.log($('#' + mainmodule));

            var mainidCreate = mainmodule + "_create";
            var mainidEdit = mainmodule + "_edit";
            var mainidDelete = mainmodule + "_delete";
            var mainidShow = mainmodule + "_show";

            if ($('#' + mainmodule).is(':checked')) {
                $('#' + mainidCreate).prop('disabled', false);
                $('#' + mainidCreate).prop('checked', true);

                $('#' + mainidEdit).prop('disabled', false);
                $('#' + mainidEdit).prop('checked', true);

                $('#' + mainidDelete).prop('disabled', false);
                $('#' + mainidDelete).prop('checked', true);

                $('#' + mainidShow).prop('disabled', false);
                $('#' + mainidShow).prop('checked', true);
            } else {
                $('#' + mainidCreate).prop('disabled', true);
                $('#' + mainidCreate).prop('checked', false);

                $('#' + mainidEdit).prop('disabled', true);
                $('#' + mainidEdit).prop('checked', false);

                $('#' + mainidDelete).prop('disabled', true);
                $('#' + mainidDelete).prop('checked', false);

                $('#' + mainidShow).prop('disabled', true);
                $('#' + mainidShow).prop('checked', false);
            }
        };
    </script>

@endsection

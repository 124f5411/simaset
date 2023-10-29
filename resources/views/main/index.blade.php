@extends('themes.login.master')
@section('body')
<div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
    <div class="card card0 border-0">
        <div class="row d-flex" style="min-height: 80vh">
            <div class="col-lg-6">
                <div class="card1 pb-5">
                    <div class="row">
                        <img src="{{ asset('logo/simaset-logopbd.png') }}" class="logo">
                    </div>
                    <div class="row px-3 justify-content-center mt-4 mb-5 border-line">
                        <img src="{{ asset('logo/draw2.png') }}" class="image">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <form onsubmit="_check('{{ route('userCheck') }}')" id="login_form" action="">
                    @csrf
                    @method('post')
                    <div class="card2 card border-0 px-4 py-5">
                        <div class="row mb-4 px-3">
                            <h6 class="mb-0 mr-4 mt-2">Sistem Infromasi Manajemen Aset</h6>
                        </div>
                        <div class="row px-3">
                            <label class="mb-1">
                                <h6 class="mb-0 text-sm">N I P</h6>
                            </label>
                            <input class="mb-4" type="text" name="nip" id="nip" required
                                placeholder="Masukkan NIP anda">
                        </div>
                        <div class="row px-3">
                            <label class="mb-1">
                                <h6 class="mb-0 text-sm">Password</h6>
                            </label>
                            <input type="password" name="password" id="password" required placeholder="Enter password">
                        </div>
                        <div class="row px-3 mb-4">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input id="chk1" type="checkbox" name="chk" id="chk" required
                                    class="custom-control-input">
                                <label for="chk1" class="custom-control-label text-sm">Remember me</label>
                            </div>
                        </div>
                        <div class="row mb-3 px-3">
                            <button type="submit" class="btn btn-blue text-center">Login</button>
                        </div>
                        <div class="alert" role="alert" style="display: none">
                            <p id="massages"></p>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <div class="bg-blue py-4">
            <div class="row px-3">
                <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; {{ date('Y') }}. Manajemen Aset Provinsi Papua Barat
                    Daya (BPKAD).</small>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
    body {
        color: #000;
        overflow-x: hidden;
        height: 100%;
        background-color: #fff;
        background-repeat: no-repeat;
    }

    .card0 {
        box-shadow: 0px 4px 8px 0px #757575;
        border-radius: 0px;
    }

    .card2 {
        margin: 0px 40px;
    }

    .logo {
        width: 200px;
        height: 100px;
        margin-top: 20px;
        margin-left: 35px;
    }

    .image {
        width: 680px;
        height: 420px;
    }

    .border-line {
        border-right: 1px solid #EEEEEE;
    }

    .text-sm {
        font-size: 14px !important;
    }

    ::placeholder {
        color: #BDBDBD;
        opacity: 1;
        font-weight: 300
    }

    :-ms-input-placeholder {
        color: #BDBDBD;
        font-weight: 300
    }

    ::-ms-input-placeholder {
        color: #BDBDBD;
        font-weight: 300
    }

    input,
    textarea {
        padding: 10px 12px 10px 12px;
        border: 1px solid lightgrey;
        border-radius: 2px;
        margin-bottom: 5px;
        margin-top: 2px;
        width: 100%;
        box-sizing: border-box;
        color: #2C3E50;
        font-size: 14px;
        letter-spacing: 1px;
    }

    input:focus,
    textarea:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: 1px solid #304FFE;
        outline-width: 0;
    }

    button:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        outline-width: 0;
    }

    a {
        color: inherit;
        cursor: pointer;
    }

    .btn-blue {
        background-color: #1A237E;
        width: 150px;
        color: #fff;
        border-radius: 2px;
    }

    .btn-blue:hover {
        background-color: #acb2f7;
        cursor: pointer;
    }

    .bg-blue {
        color: #fff;
        background-color: #1A237E;
    }

    @media screen and (max-width: 991px) {
        .logo {
            margin-left: 0px;
        }

        .image {
            width: 300px;
            height: 220px;
        }

        .border-line {
            border-right: none;
        }

        .card2 {
            border-top: 1px solid #EEEEEE !important;
            margin: 0px 15px;
        }
    }
</style>
@endpush

@push('scripits')
<script>
    $(document).ready(function () {
        $('#login_form').on('submit', function(e){
            e.preventDefault();
            $.post($('#login_form').attr('action'), $('#login_form').serialize())
            .done((response) => {
                console.log("OK");
                $(".alert" ).addClass( "alert-success" );
                $(".alert").show();
                $("#massages").append(response);
                setTimeout(function(){
                    $(".alert" ).removeClass( "alert-success" );
                    $("#massages").empty();
                    window.location.replace("{{ route('dashboard') }}");
                }, 1000);
            })
            .fail((errors) => {
                let err = errors.responseJSON.errors;
                $(".alert" ).addClass( "alert-danger" );
                $(".alert").show();
                $.each(err, function(key, val) {
                    $("#massages").append(val);
                    setTimeout(function(){
                        $(".alert").hide();
                        $(".alert" ).removeClass( "alert-danger" );
                        $("#massages").empty();
                    }, 3000);
                });
            });
        });
    });

    function _check(url) {
        $('#login_form').attr('action', url);
        $('#login_form [name=_method]').val('post');
    }
</script>
@endpush

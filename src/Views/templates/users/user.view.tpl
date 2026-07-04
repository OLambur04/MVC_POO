<section class="container-m row px-4 py-4">
    <h1>{{FormTitle}}</h1>
</section>

<section class="container-m row px-4 py-4">

    {{with user}}

    <form action="index.php?page=Users_User&mode={{~mode}}&usercod={{usercod}}" method="POST"
        class="col-12 col-m-8 offset-m-2">

        <div class="row my-2 align-center">

            <label class="col-12 col-m-3">Código</label>

            <input class="col-12 col-m-9" type="text" value="{{usercod}}" disabled readonly />

            <input type="hidden" name="usercod" value="{{usercod}}" />
            <input type="hidden" name="mode" value="{{~mode}}" />

        </div>

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3">Email</label>

            <input class="col-12 col-m-9" type="email" name="useremail" value="{{useremail}}" {{~readonly}} />

            {{if useremail_error}}
            <div class="col-12 col-m-9 offset-m-3 error">{{useremail_error}}</div>
            {{endif useremail_error}}
        </div>

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3">Nombre</label>

            <input class="col-12 col-m-9" type="text" name="username" value="{{username}}" {{~readonly}} />

            {{if username_error}}
            <div class="col-12 col-m-9 offset-m-3 error">{{username_error}}</div>
            {{endif username_error}}
        </div>

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3">Estado</label>

            <select name="userest" class="col-12 col-m-9" {{if ~readonly}} disabled {{endif ~readonly}}>
                <option value="ACT" {{userest_act}}>Activo</option>
                <option value="INA" {{userest_ina}}>Inactivo</option>
            </select>
        </div>

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3">Tipo</label>

            <select name="usertipo" class="col-12 col-m-9" {{if ~readonly}} disabled {{endif ~readonly}}>
                <option value="ADM" {{usertipo_adm}}>Admin</option>
                <option value="CLT" {{usertipo_clt}}>Cliente</option>
                <option value="USR" {{usertipo_usr}}>Usuario</option>
            </select>
        </div>

        {{endwith user}}

        <div class="row my-4 align-center flex-end">

            {{if showCommitBtn}}
            <button class="primary col-12 col-m-2" type="submit">
                Confirmar
            </button>
            &nbsp;
            {{endif showCommitBtn}}

            <button class="col-12 col-m-2" type="button" id="btnCancelar">
                {{if showCommitBtn}}Cancelar{{endif showCommitBtn}}
                {{ifnot showCommitBtn}}Regresar{{endifnot showCommitBtn}}
            </button>

        </div>

    </form>

</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("btnCancelar").addEventListener("click", () => {
            window.location.href = "index.php?page=Users_Users";
        });
    });
</script>
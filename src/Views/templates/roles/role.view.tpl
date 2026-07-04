<section class="container-m row px-4 py-4">
    <h1>{{FormTitle}}</h1>
</section>

<section class="container-m row px-4 py-4">

    {{with role}}

    <form action="index.php?page=Roles_Role&mode={{~mode}}&rolescod={{rolescod}}" method="POST"
        class="col-12 col-m-8 offset-m-2">

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3" for="rolescod">Código</label>

            <input class="col-12 col-m-9" type="text" name="rolescod" id="rolescod" value="{{rolescod}}"
                {{~readonly}} />

            {{if rolescod_error}}
            <div class="col-12 col-m-9 offset-m-3 error">
                {{rolescod_error}}
            </div>
            {{endif rolescod_error}}
        </div>

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3" for="rolesdsc">Descripción</label>

            <input class="col-12 col-m-9" type="text" name="rolesdsc" id="rolesdsc" value="{{rolesdsc}}"
                {{~readonly}} />

            {{if rolesdsc_error}}
            <div class="col-12 col-m-9 offset-m-3 error">
                {{rolesdsc_error}}
            </div>
            {{endif rolesdsc_error}}
        </div>

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3" for="rolesest">Estado</label>

            <select class="col-12 col-m-9" name="rolesest" id="rolesest" {{if ~readonly}} disabled {{endif ~readonly}}>

                <option value="ACT" {{rolesest_act}}>Activo</option>
                <option value="INA" {{rolesest_ina}}>Inactivo</option>

            </select>
        </div>

        {{endwith role}}

        <div class="row my-4 align-center flex-end">

            {{if showCommitBtn}}
            <button class="primary col-12 col-m-2" type="submit">
                Confirmar
            </button>
            {{endif showCommitBtn}}

            <button class="col-12 col-m-2" type="button" id="btnCancelar">
                Cancelar
            </button>

        </div>

    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("btnCancelar").addEventListener("click", () => {
            window.location.assign("index.php?page=Roles_Roles");
        });
    });
</script>
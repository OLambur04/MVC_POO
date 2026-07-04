<section class="container-m row px-4 py-4">
    <h1>{{FormTitle}}</h1>
</section>

<section class="container-m row px-4 py-4">

    {{with function}}

    <form action="index.php?page=Functions_FunctionController&mode={{~mode}}&fncod={{fncod}}" method="POST"
        class="col-12 col-m-8 offset-m-2">

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3" for="fncod">Código</label>

            <input class="col-12 col-m-9"
                type="text"
                name="fncod"
                id="fncod"
                value="{{fncod}}"
                {{~readonly}} />

            {{if fncod_error}}
            <div class="col-12 col-m-9 offset-m-3 error">
                {{fncod_error}}
            </div>
            {{endif fncod_error}}
        </div>

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3" for="fndsc">Descripción</label>

            <input class="col-12 col-m-9"
                type="text"
                name="fndsc"
                id="fndsc"
                value="{{fndsc}}"
                {{~readonly}} />

            {{if fndsc_error}}
            <div class="col-12 col-m-9 offset-m-3 error">
                {{fndsc_error}}
            </div>
            {{endif fndsc_error}}
        </div>

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3" for="fntyp">Tipo</label>

            <input class="col-12 col-m-9"
                type="text"
                name="fntyp"
                id="fntyp"
                value="{{fntyp}}"
                {{~readonly}} />

            {{if fntyp_error}}
            <div class="col-12 col-m-9 offset-m-3 error">
                {{fntyp_error}}
            </div>
            {{endif fntyp_error}}
        </div>

        <div class="row my-2 align-center">
            <label class="col-12 col-m-3" for="fnest">Estado</label>

            <select class="col-12 col-m-9"
                name="fnest"
                id="fnest"
                {{if ~readonly}} disabled {{endif ~readonly}}>

                <option value="ACT" {{fnest_act}}>Activo</option>
                <option value="INA" {{fnest_ina}}>Inactivo</option>

            </select>

            {{if fnest_error}}
            <div class="col-12 col-m-9 offset-m-3 error">
                {{fnest_error}}
            </div>
            {{endif fnest_error}}
        </div>

        {{endwith function}}

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
            window.location.assign("index.php?page=Functions_Functions");
        });
    });
</script>
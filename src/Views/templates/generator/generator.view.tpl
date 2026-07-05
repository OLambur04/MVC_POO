<h1>Generador CRUD</h1>

{{if error}}
<section class="grid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
                {{error}}
            </div>
        </div>
    </div>
</section>
{{endif error}}

{{if success}}
<section class="grid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                {{success}}
            </div>
        </div>
    </div>
</section>
{{endif success}}

<section class="grid">
    <div class="row">
        <form class="col-12 col-m-8" action="index.php?page=Generator_Generator" method="POST">

            <div class="row align-center">
                <label class="col-3" for="tableName">Tabla</label>
                <input class="col-9" type="text" name="tableName" id="tableName" value="{{tableName}}" />
            </div>

            <div class="row my-3">
                <button type="submit">Generar CRUD</button>
            </div>

        </form>
    </div>
</section>

{{if fields}}

<section class="WWList">
    <h2>Estructura de la tabla</h2>

    <table>
        <thead>
            <tr>
                <th>Campo</th>
                <th>Tipo</th>
                <th>Null</th>
                <th>Key</th>
            </tr>
        </thead>

        <tbody>
            {{foreach fields}}
            <tr>
                <td>{{Field}}</td>
                <td>{{Type}}</td>
                <td>{{Null}}</td>
                <td>{{Key}}</td>
            </tr>
            {{endfor fields}}
        </tbody>
    </table>
</section>

{{endif fields}}

{{if generated}}

<section class="grid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                Archivos generados correctamente
            </div>
        </div>
    </div>
</section>

{{endif generated}}
<h1>Trabajar con Usuarios</h1>

<section class="grid">
    <div class="row">
        <form class="col-12 col-m-8" action="index.php" method="get">
            <div class="flex align-center">

                <div class="col-8 row">

                    <input type="hidden" name="page" value="Users_Users">

                    <label class="col-3" for="partialName">Nombre</label>
                    <input class="col-9" type="text" name="partialName" id="partialName" value="{{partialName}}" />

                    <label class="col-3" for="status">Estado</label>
                    <select class="col-9" name="status" id="status">
                        <option value="EMP" {{status_EMP}}>Todos</option>
                        <option value="ACT" {{status_ACT}}>Activo</option>
                        <option value="INA" {{status_INA}}>Inactivo</option>
                    </select>

                    <label class="col-3" for="userType">Tipo</label>
                    <select class="col-9" name="userType" id="userType">
                        <option value="" {{userType_}}>Todos</option>
                        <option value="ADM" {{userType_ADM}}>Admin</option>
                        <option value="CLT" {{userType_CLT}}>Cliente</option>
                        <option value="USR" {{userType_USR}}>Usuario</option>
                    </select>

                </div>

                <div class="col-4 align-end">
                    <button type="submit">Filtrar</button>
                </div>

            </div>
        </form>
    </div>
</section>

<section class="WWList">
    <table>
        <thead>
            <tr>

                <th>
                    {{ifnot OrderByUsercod}}
                    <a href="index.php?page=Users_Users&orderBy=usercod&orderDescending=0">
                        ID <i class="fas fa-sort"></i>
                    </a>
                    {{endifnot OrderByUsercod}}

                    {{if OrderUsercodDesc}}
                    <a href="index.php?page=Users_Users&orderBy=clear&orderDescending=0">
                        ID <i class="fas fa-sort-down"></i>
                    </a>
                    {{endif OrderUsercodDesc}}

                    {{if OrderUsercod}}
                    <a href="index.php?page=Users_Users&orderBy=usercod&orderDescending=1">
                        ID <i class="fas fa-sort-up"></i>
                    </a>
                    {{endif OrderUsercod}}
                </th>

                <th class="left">Nombre</th>
                <th class="left">Email</th>
                <th>Tipo</th>
                <th>Estado</th>

                <th>
                    <a href="index.php?page=Users_User&mode=INS">Nuevo</a>
                </th>

            </tr>
        </thead>

        <tbody>
            {{foreach users}}
            <tr>

                <td>{{usercod}}</td>

                <td>
                    <a href="index.php?page=Users_User&mode=DSP&usercod={{usercod}}">
                        {{username}}
                    </a>
                </td>

                <td>{{useremail}}</td>
                <td>{{usertipo}}</td>
                <td>{{userest}}</td>

                <td>
                    <a href="index.php?page=Users_User&mode=UPD&usercod={{usercod}}">Editar</a>
                    &nbsp;
                    <a href="index.php?page=Users_User&mode=DEL&usercod={{usercod}}">Eliminar</a>
                </td>

            </tr>
            {{endfor users}}
        </tbody>
    </table>

    {{pagination}}
</section>
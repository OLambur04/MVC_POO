<h1>{{TITLE}}</h1>

<table>
    <thead>
        <tr>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        {{foreach data}}
        <tr>
            <td>{{this}}</td>
        </tr>
        {{endfor data}}
    </tbody>
</table>
<?php

/*
 * TODO: optional headers styling/button
 */
$required_headers = $required_headers ?? [];
$required_header_values = json_encode($required_headers, JSON_THROW_ON_ERROR);
$optional_headers = $optional_headers ?? [];
$optional_header_values = json_encode($optional_headers, JSON_THROW_ON_ERROR);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5sortable/0.13.3/html5sortable.min.js"
        integrity="sha512-3btxfhQfasdVcv1dKYZph5P7jFeeLRcF1gDVzFA+k9AiwwhB1MNI7O58zCK0uVItuMHNDR5pMoF2nqlCGzUwZQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

This page will help format a Spreadsheet for uploading. Only the content should be uploaded (no headers).


<style>
    #header_skips {
        display: inline;
        width: auto;
    }
    .bg-disabled {
        background: repeating-linear-gradient(
                -40deg,
                rgba(220, 220, 220, 0.75),
                rgba(220, 220, 220, 0.75) 10px,
                white 10px,
                white 20px
        );
    }
    #set_header_size {
        display: none
    }
</style>

<?php

echo $this->Form->create(null, ['id' => 'jsonForm', 'type' => 'file']);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" id="set_delimiter">
            Delimiter
            <?php
            echo $this->Form->radio('delimiter', [
                'tab' => 'Tab',
                'semi' => 'Semicolon',
                'comma' => 'Comma',
                'space' => 'Space',
                'other' => 'Other',
            ], ['required' => true, 'value' => 'comma']);
            echo $this->Form->control('other', ['id' => 'otherField',
                'class' => 'inline-block',
                'label' => false,
                'type' => 'text',
                'disabled' => true]);
            ?>
        </div>
        <br>
        <div class="col-md-12" id="set_header_size">
            Set Headers
            <?php
            echo '<div>';
            echo 'Skip ';
            echo $this->Form->number('header_skips', [
                'value' => 0,
                'min' => 0,
                'id' => 'header_skips']);
            echo ' rows (eg. headers or units)';
            echo '</div>';
            echo '<br>';
            ?>
            <div id="header_setter"></div>
            <?php
            echo $this->Form->hidden('csv_order', ['id' => 'csv_order'])
            ?>
        </div>
        <br>
        <div class="col-md-12">
            <?php
            echo $this->Form->file('toolFile', ['label' => 'File', 'id' => 'toolFile', 'required' => true]);
            echo $this->Form->button('Submit', ['id' => 'submitButton', 'class' => 'btn btn-sm btn-primary']);
            echo $this->Form->end();
            ?>
        </div>
    </div>
    Preview
    <div id="preview"></div>
</div>

<script type="text/javascript" src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

<script>
    [...document.querySelectorAll('input[name="delimiter"]')].forEach(function(item) {
        item.addEventListener('change', function() {
            document.querySelector('#otherField').disabled = this.value !== 'other';
        });
    });
    document.querySelector('#submitButton').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        const delimiter = document.querySelector('input[name="delimiter"]:checked').value;
        if (!delimiter) {
            alert("You must select a delimiter!");
            return;
        }
        const file = document.querySelector('#toolFile')[0].files;
        if (!file || !file.length) {
            alert("You must select a file!");
            return;
        }
        const form = document.querySelector('#jsonForm');
        const url = form.attr('action');
        let formData = form[0];
        formData = new FormData(formData);

        const request = new XMLHttpRequest();
        request.open('POST', url, true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.setRequestHeader('X-CSRF-Token', document.querySelector('[name="_csrfToken"]').value);
        request.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                while(form.firstChild)
                    form.removeChild(form.firstChild);
            }
            document.querySelector('#responseTable').innerHTML = data;
        };
        request.send(formData);
    });
    document.querySelector('#toolFile').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!event || !event.target || !event.target.files || event.target.files.length === 0 || !file) {
            return;
        }

        const name = file.name;
        const fileExt = name.substring(name.lastIndexOf('.') + 1);

        const reader = new FileReader();
        document.getElementById('set_delimiter').style.display = "none";
        document.getElementById('set_header_size').style.display = "block";
        const max_rows = 5;
        const required_header_values = <?= $required_header_values ?>;
        const num_required_header_values = required_header_values.length;
        const optional_header_values = <?= $optional_header_values ?>;
        const num_optional_header_values = optional_header_values.length;
        let header_setter = '<ul id="headers-sortable" ' +
            'class="list-group list-group-horizontal">';
        let table_header = "<table class='table table-bordered'>";

        function makeColGroups(num_cols) {
            let colgroups = '<colgroup>';
            if (num_cols < num_required_header_values) {
                alert('Missing required fields.\nExpected fields are:\n\n' +
                    required_header_values.map(value => value.Name).join(', '));
            }
            for (let col = 0; col < num_cols; col++) {
                colgroups += '<col>';
                if (col < num_required_header_values) {
                    header_setter += '<li class="list-group-item" ' +
                        'data-bs-toggle="tooltip" ' +
                        'title="' + required_header_values[col].Description + '">' +
                        required_header_values[col].Name + '</li>';
                } else if (col < num_required_header_values + num_optional_header_values) {
                    header_setter += '<li class="list-group-item" ' +
                        'data-bs-toggle="tooltip" ' +
                        'title="' + optional_header_values[col - num_required_header_values].Description + '">'
                        + optional_header_values[col - num_required_header_values].Name + '</li>';
                } else {
                    header_setter += '<li class="list-group-item bg-secondary text-white-50" ' +
                        'data-bs-toggle="tooltip" ' +
                        'title="Ignore this column">SKIP</li>';
                }
            }
            colgroups += '</colgroup>';
            return colgroups;
        }

        if (fileExt === 'csv' || fileExt === 'txt') {
            reader.onload = function(e) {
                const data = e.target.result;
                const rows = data.split(/\r?\n/);
                let delimiter;

                const ele = document.getElementsByName('delimiter');
                for(let i = 0; i < ele.length; i++) {
                    if(ele[i].checked)
                        delimiter = ele[i].value;
                }

                let delimiterStr = ',';
                if (delimiter === 'comma') {
                    delimiterStr = ',';
                }
                else if (delimiter === 'tab') {
                    delimiterStr = `\t`;
                }
                else if (delimiter === 'semi') {
                    delimiterStr = ';';
                }
                else if (delimiter === 'space') {
                    delimiterStr = ' ';
                }
                else if (delimiter === 'other') {
                    delimiterStr = new RegExp(document.querySelector("#otherField").value);
                }

                let table_output = [];
                let colgroups;
                for (let i = 0; i < rows.length && i < max_rows; i++){
                    const row = rows[i];
                    const cell = row.slice(0, -1).split(delimiterStr);
                    if (i === 0) {
                        colgroups = makeColGroups(cell.length);
                    }
                    table_output.push("<tr><td>"
                        + cell.join("</td><td>")
                        + "</td></tr>");
                }
                table_output = table_header
                    + colgroups
                    + table_output.join("")
                    + "</table>";
                document.getElementById('preview').innerHTML = table_output;
                outputSortable();
            };
            reader.readAsText(file);
        }
        else if (fileExt === 'xlsx') {
            if(!['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel'].includes(file.type))
            {
                document.getElementById('preview').innerHTML =
                    '<div class="alert alert-danger">Only .xlsx or .xls file format are allowed</div>';
                return false;
            }

            reader.readAsArrayBuffer(file);
            reader.onload = function(){
                const data = new Uint8Array(reader.result);
                const work_book = XLSX.read(data, {type: 'array'});
                const sheet_name = work_book.SheetNames;
                const sheet_data = XLSX.utils.sheet_to_json(work_book.Sheets[sheet_name[0]], {header: 1});

                if(sheet_data.length > 0) {
                    let table_output = table_header;
                    const num_cols = sheet_data[0].length;
                    table_output += makeColGroups(num_cols);
                    for(let row = 0; row < sheet_data.length && row < max_rows; row++) {
                        table_output += '<tr>';
                        for(let cell = 0; cell < sheet_data[row].length; cell++) {
                            table_output += '<td>'+sheet_data[row][cell]+'</td>';
                        }
                        table_output += '</tr>';
                    }
                    table_output += '</table>';
                    document.getElementById('preview').innerHTML = table_output;
                    outputSortable();
                }
            }
        }

        function outputSortable() {
            header_setter += '</ul>';
            document.getElementById('header_setter').innerHTML = header_setter;
            sortable('#headers-sortable', {
                orientation: 'horizontal',
                itemSerializer: (serializedItem) => {
                    return {
                        position:  serializedItem.index + 1,
                        value: serializedItem.node.textContent
                    }
                }
            });
            sortable('#headers-sortable')[0].addEventListener('sortupdate', function (e) {
                e.detail.origin.items.forEach(function (elem, index) {
                    const colgroup_classes =
                        document.querySelectorAll('table > colgroup > col')[index].classList;
                    if (elem.classList.contains('bg-secondary')) {
                        colgroup_classes.add('bg-disabled');
                    } else {
                        colgroup_classes.remove('bg-disabled');
                    }
                });
                document.querySelector('#csv_order').value = JSON.stringify(sortable('#headers-sortable', 'serialize'));
            });
            const tooltipTriggerList = []
                .slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        }

    })
</script>

<script defer>
    document.querySelector('#header_skips').addEventListener('change', function () {
        const allRows = document.querySelectorAll('table > tbody > tr');
        for (let row = 0; row < allRows.length; row++) {
            if (row < this.value) {
                allRows[row].classList.add('bg-disabled');
            }
            else {
                allRows[row].classList.remove('bg-disabled');
            }
        }
    });
</script>

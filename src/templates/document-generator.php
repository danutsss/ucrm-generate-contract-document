<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contract document generate</title>
    <link rel="stylesheet" href="<?php echo rtrim(htmlspecialchars($ucrmPublicUrl, ENT_QUOTES), '/'); ?>/assets/fonts/lato/lato.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link rel="stylesheet" href="public/main.css" />
</head>

<body>
    <div id="header">
        <h1>Select client to generate contract</h1>
    </div>
    <div id="content" class="container-fluid ml-0 mr-0">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="generate-form">
                            <div class="align-items-end">
                                <div class="form-row">
                                    <div class="col-3">
                                        <label class="mb-0" for="frm-from"><small>From client #:</small></label>
                                        <input type="number" name="from" id="frm-from" placeholder="X" class="form-control form-control-sm" min="0" value="0" max="<?= count($clients) ?>">
                                    </div>

                                    <div class="col-3">
                                        <label class="mb-0" for="frm-to"><small>To client #:</small></label>
                                        <input type="number" name="to" id="frm-to" placeholder="Y" class="form-control form-control-sm" min="0" max="<?= count($clients) ?>">
                                    </div>

                                    <div class="col-3">
                                        <label class="mb-0" for="frm-to"><small>Contract template:</small></label>
                                        <select name="template" id="frm-template" class="form-control form-control-sm">
                                            <option value="0">Select template</option>
                                            <option value="urban">URBAN NETWORK SOLUTIONS S.R.L</option>
                                            <option value="rural">ZERO SAPTE SERVICES S.R.L</option>
                                        </select>
                                    </div>

                                    <div class="col-auto ml-auto">
                                        <button type="submit" class="btn btn-primary btn-sm pl-4 pr-4">Generate contract</button>
                                    </div>
                                </div>

                                <br />
                                !! nu selecta mai mult de 150 de clienti, altfel vei primi timeout !!
                                <br />
                                # of clients: <?= count($clients) ?>


                                <!-- create a table with all of the clients -->
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Client #</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Regenerate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($clients as $client) {
                                            printf(
                                                '<tr>
                                                        <td>%s (id: %s)</td>
                                                        <td>%s %s</td>
                                                        <td>
                                                            <div>
                                                                <input type="checkbox" name="generate[]" value="%s" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    ',
                                                $client['userIdent'],
                                                $client['id'],
                                                $client['firstName'] ?? $client['companyName'],
                                                $client['lastName'],
                                                $client['id']
                                            );
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const rangeForm = document.getElementById('generate-form');

        rangeForm.addEventListener('change', () => {
            const from = document.getElementById('frm-from').value;
            const to = document.getElementById('frm-to').value;

            const checkboxes = document.querySelectorAll('input[type="checkbox"]');

            for (let i = 0; i < checkboxes.length; i++) {
                if (i >= from && i <= to) {
                    checkboxes[i].checked = true;
                } else {
                    checkboxes[i].checked = false;
                }

                if (from > to) {
                    checkboxes[i].checked = false;
                }

                if (from == 0 && to == 0) {
                    checkboxes[i].checked = false;
                }

                if (from == 0 && to == checkboxes.length) {
                    checkboxes[i].checked = true;
                }
            }
        });
    </script>
</body>
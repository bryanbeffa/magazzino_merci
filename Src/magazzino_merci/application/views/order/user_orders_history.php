<div class="container">
    <h1>Cronologia delle richieste d'ordine</h1>
    <hr>
    <p>In questa pagina puoi visualizzare la cronologia dei tuoi ordini</p>

    <table id="ordersTable" class="table text-center border-top table-responsive-sm">
        <thead>

        <th>Immagine</th>
        <th>Articolo</th>
        <th>Quantità</th>
        <th>Data ordine</th>
        <th>Esito</th>
        <th>Dettagli</th>

        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr <?php switch ($order['accettato']):
                case '0':
                    echo 'class="deep-orange lighten-4"';
                    break;
                case '1':
                    echo 'class="green accent-1"';
                    break;
            endswitch; ?>>
                <?php $article = ArticleManager::getArticleById($order['id_articolo']) ?>
                <td><img class="img-table-height"
                         src="<?php echo URL . '/application/libs/img/articles/' . ($article['percorso_immagine'] ?? 'default.jpg') ?>">
                </td>
                <td><?php echo $article['nome'] ?></td>
                <td><?php echo $order['quantita_ordine'] ?></td>
                <td><?php echo date_format(date_create($order['data_ordine']), 'd.m.Y G:i') ?></td>

                <td>
                    <?php switch ($order['accettato']):
                        case '0':
                            echo 'Ordine rifiutato';
                            break;
                        case '1':
                            echo 'Ordine accettato';
                            break;
                        default:
                            echo 'Ordine non ancora elaborato';
                    endswitch; ?></td>

                <td><a class="text-primary"
                       data-toggle="modal" data-target="#orderModal"
                       onclick="showOrderDetailHistory('<?php echo rawurlencode($article['nome']) ?>', '<?php echo rawurlencode($order['quantita_ordine']) ?>', '<?php echo rawurlencode(date_format(date_create($article['data_scadenza']), 'd.m.Y')) ?>', '<?php echo rawurlencode($order['data_ordine']) ?>', '<?php echo $order['accettato'] ?>', '<?php echo rawurlencode($order['data_consegna'])?>')">

                        <img class="bin-icon" src="/magazzino_merci/application/libs/img/svg/info.svg">
                    </a></td>
            </tr>


        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Start order modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dettagli ordine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <!-- modal body-->
                <div class="modal-body">

                    <input type="hidden" id="articleId" name="article_id">
                    <p>Qui puoi visualizzare i dettagli del tuo ordine</p>
                    <table class="table table-responsive-sm table-bordered text-center">
                        <tbody>

                        <tr>
                            <td class="w-50 font-weight-bold">Nome articolo</td>
                            <td class="w-50" id="articleName"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Quantità</td>
                            <td class="w-50" id="orderQuantity"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Data scadenza</td>
                            <td class="w-50" id="articleExpireDate"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Data ordine</td>
                            <td class="w-50" id="orderDate"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Data consegna</td>
                            <td class="w-50" id="deliveryDate"></td>
                        </tr>
                        <tr>
                            <td class="w-50 font-weight-bold">Stato ordine</td>
                            <td class="w-50" id="orderDecision"></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <!-- end modal body-->

                <!-- modal footer-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
                </div>
                <!-- end modal body-->

            </div>
        </div>
    </div>
    <!-- End order modal -->

    <script type="text/javascript" src="/magazzino_merci/application/libs/js/manageOrder.js"></script>
    <script>
        $(document).ready(function () {
            $('#ordersTable').DataTable({
                "lengthMenu": [[10, 25, 50], [10, 25, 50]],
                "ordering": false,
                "language": {
                    "lengthMenu": "Mostra _MENU_ record per pagina",
                    "zeroRecords": "Nessun record trovato",
                    "info": "Pagina _PAGE_ di _PAGES_",
                    "infoEmpty": "Nessun record disponibile",
                    "infoFiltered": "(filtrato da _MAX_ record totali)",
                    "search": "Cerca:",
                    "paginate": {
                        "previous": "Precedente",
                        "next": "Successiva"
                    }
                }
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
</div>
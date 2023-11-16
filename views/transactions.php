<!DOCTYPE html>
<html>

<head>
    <title>Transactions</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table tr th,
        table tr td {
            padding: 5px;
            border: 1px #eee solid;
        }

        tfoot tr th,
        tfoot tr td {
            font-size: 20px;
        }

        tfoot tr th {
            text-align: right;
        }

        .amount.amount--red {
            color: red;
        }

        .amount.amount--green {
            color: green;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Check #</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $value) : ?>
                <tr>
                    <td><?php echo $value['Date']; ?></td>
                    <td><?php echo $value['Check']; ?></td>
                    <td><?php echo $value['Description']; ?></td>
                    <td class="<?php echo $value['Class']; ?>"><?php echo $value['Amount']; ?></td>
                </tr>
            <?php endforeach; ?>
            <!-- YOUR CODE -->
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total Income:</th>
                <td><?php echo $totalPrices["totalIncome"]; ?></td>
            </tr>
            <tr>
                <th colspan="3">Total Expense:</th>
                <td><?php echo $totalPrices["totalEpense"]; ?></td>
            </tr>
            <tr>
                <th colspan="3">Net Total:</th>
                <td><?php echo $totalPrices["netTotal"]; ?></td>
            </tr>
        </tfoot>
    </table>

    <br>
    <br>

    <a href="/">Back to home</a>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .backGroundGray{
            background-color: gray;
        }
    </style>
</head>
<body>
    <?php 
        $scores = [3, 4, 6, 3, 1, 9, 10, 2, 8, 3 ,1, 7, 5];
        $scores = [];
        //show table (stt, scores, note (6 -> dat, 5 <- khong dat), dong chan in background xam)
    ?>

    <table border="1">
        <tr>
            <th>STT</th>
            <th>Score</th>
            <th>Note</th>
        </tr>
        <?php 
            $stt = 1;
            foreach($scores as $score) :  
        ?>
            <tr class="<?= $stt % 2 === 0 ? 'backGroundGray' : '' ?>">
                <td><?= $stt++; ?></td>
                <td><?= $score ?></td>
                <td><?= $score > 5 ? 'Good' : 'Bad' ?></td>
            </tr>
        <?php endforeach ?>
        <?php 
            if(empty($score)){
                echo '<tr><td colspan="3">No data</td></tr>';
            }
        ?>
    </table>

</body>
</html>

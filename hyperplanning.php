<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="scripts/hyperplanning.js?v=<?php echo date('d.m.y.h.i'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo date('d.m.y.h.i'); ?>">
    <meta charset="utf-8" />
    <title>Hyperplanning MMI</title>
</head>


<body>
    <!-- Tableau HTML -->
    <style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
    .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
    .tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:middle}
    .tg .tg-7od5{background-color:#9aff99;border-color:inherit;text-align:left;vertical-align:top}
    .tg .tg-1a9d{
        background-color:#9aff99;border-color:#000000;text-align:center;vertical-align:middle;
    }
    .tg .tg-oy90{background-color:#ffcb2f;border-color:#000000;text-align:left;vertical-align:top}
    .tg .tg-tqgz{background-color:#ffffc7;border-color:#000000;text-align:left;vertical-align:top}
    /* AJOUT */

    </style>
    <table id="c_hyperplanning" class="tg" style="undefined;table-layout: fixed; width: 100%"> <!-- old: width: 1063px--> 
    <colgroup>
    <col style="width: 113px"> <!-- old: 113px -->
    <col style="width: 113px"> <!-- old: 113px -->
    <?php for($n=1; $n<=29; $n++) { ?>
        <col style="width: 35px;">
    <?php } ?>

    </colgroup>
      <tr>
        <th class="tg-oy90" colspan="2" id="c_tab_titre">###TITRE###</th>
        <th class="tg-7od5" colspan="3" >8h</th>
        <th class="tg-7od5" colspan="3">9h30</th>
        <th class="tg-7od5" colspan="3">11h</th>
        <th class="tg-7od5" colspan="3">12h30</th>
        <th class="tg-7od5" colspan="3">14h</th>
        <th class="tg-7od5" colspan="3">15h30</th>
        <th class="tg-7od5" colspan="3">17h</th>
        <th class="tg-7od5" colspan="3">18h30</th>
      </tr>
      <tr>
        <td class="tg-1a9d" rowspan="6">MMI1</td>
        <td class="tg-tqgz">MMI1 A1<br></td>
        <?php
        $num_ligne = 1;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI1 A2</td>
        <?php
        $num_ligne = 2;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI1 B1</td>
        <?php
        $num_ligne = 3;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI1 B2</td>
        <?php
        $num_ligne = 4;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI1 C1</td>
        <?php
        $num_ligne = 5;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI1 C2</td>
        <?php
        $num_ligne = 6;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-1a9d" rowspan="6">MMI2</td>
        <td class="tg-tqgz">MMI2 A1</td>
        <?php
        $num_ligne = 7;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI2 A2</td>
        <?php
        $num_ligne = 8;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI2 B1</td>
        <?php
        $num_ligne = 9;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI2 B2</td>
        <?php
        $num_ligne = 10;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI2 C1</td>
        <?php
        $num_ligne = 11;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">MMI2 C2</td>
        <?php
        $num_ligne = 12;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-1a9d" rowspan="2">AL1</td>
        <td class="tg-tqgz">AL1 A2</td>
        <?php
        $num_ligne = 13;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">AL1 A1</td>
        <?php
        $num_ligne = 14;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-1a9d" rowspan="2">AL2<br></td>
        <td class="tg-tqgz">AL2 A1</td>
        <?php
        $num_ligne = 15;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">AL2 A2</td>
        <?php
        $num_ligne = 16;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-1a9d" rowspan="2">LP CVCA</td>
        <td class="tg-tqgz">CVCA A1</td>
        <?php
        $num_ligne = 17;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
      <tr>
        <td class="tg-tqgz">CVCA A2</td>
        <?php
        $num_ligne = 18;  
        for($i=17; $i<=40; $i++) { //24
            $h = intval($i/2-0.1);
            $m = $i/2-$h;
            if($m==0.5)
                $m="00";
            else
                $m="30";
            if($h<10)
                $h = "0$h";
            echo '<td class="tg-c3ow" id="l'.$num_ligne.'_'.$h.'-'.$m.'"></td>'."\n";
        } unset($i); unset($h); unset($m); 
        ?>
      </tr>
    </table>
</body>


<!-- MODAL -->
<div id="c_modal">
    none
</div>


<!-- CONTEXT MENU -->
 <?php
    $date_cible = strtotime('today');
    if(isset($_GET['cible']))
        $date_cible = strtotime(explode('/', $_GET['cible'])[2].'/'.explode('/', $_GET['cible'])[1].'/'.explode('/', $_GET['cible'])[0]);
    $lien['date']['back'] = date('d/m/Y', strtotime('-1 day', $date_cible));
    $lien['date']['next'] = date('d/m/Y', strtotime('+1 day', $date_cible));
    $lien['date']['today'] = date('d/m/Y'); 
?>
<ul class='context-menu'>
    <li data-action="prev" data-href="<?php echo '?cible='.$lien['date']['back']; ?>">
        ← EDT du jour precedent : <?php echo $lien['date']['back']; ?>
    </li>
    <li data-action="next" data-href="<?php echo '?cible='.$lien['date']['next']; ?>">
        → EDT du jour suivant : <?php echo $lien['date']['next']; ?>
    </li>
    <li data-action="today" data-href="<?php echo '?cible='.$lien['date']['today']; ?>">
        ◘ Aujourd'hui : <?php echo $lien['date']['today']; ?>
    </li>
    <li data-action="reload" data-href="<?php echo '?cible='.date('d/m/Y', $date_cible).'&refresh_cache=true'; ?>">
        ☼ Forcer la mise à jour de cette page
    </li>
</ul>
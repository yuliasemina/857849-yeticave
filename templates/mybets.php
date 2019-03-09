<?php 
$lot_price = $lot['max_price'] ?: $lot['price'];
$min_bet = $lot_price + $lot['bet_step'];

?>

    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
        <li class="nav__item">
          <a href="all_lots.php?id=<?= intval($category['id']) ?>"><?= htmlspecialchars($category['category_name']) ?></a>
        </li>
        <?php endforeach ?>
      </ul>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach ($lots as $lot): ?>

<?php if (strtotime($lot['date_end']) < strtotime('now')): ?>
        <tr class="rates__item <?= !empty($lot['winner_id']) ? "rates__item--win" : "rates__item--end"; ?> ">
  <?php else: ?> 
 <tr class="rates__item">
 <?php endif; ?>         

          <td class="rates__info">
            <div class="rates__img">
              <img src="<?= htmlspecialchars($lot['image']) ?>" width="54" height="40" alt="<?= htmlspecialchars($lot['title']) ?>">
            </div>
            <?php if (!empty($lot['winner_id']) && (strtotime($lot['date_end']) < strtotime('now'))): ?>
<div>
              <h3 class="rates__title"><a href="lot.php?id=<?= intval($lot['id']) ?>"><?= htmlspecialchars($lot['title']) ?></a></h3>
              <p><?= htmlspecialchars($lot['user_contact']). ' '.htmlspecialchars($lot['user_email']) ?></p>  
            </div>
              <?php else: ?>
            <h3 class="rates__title"><a href="lot.php?id=<?= intval($lot['id']) ?>"><?= htmlspecialchars($lot['title']) ?></a></h3>
          
 <?php endif; ?>


          </td>
          <td class="rates__category">
            <?= htmlspecialchars($lot['category']) ?>
          </td>
          <td class="rates__timer">
            <?php if (strtotime($lot['date_end']) < (strtotime('now')+'86400')): ?>
            


            <?php if (empty($lot['winner_id']) && (strtotime($lot['date_end']) < strtotime('now')) ): ?>
            <div class="timer timer--end">Торги окончены</div>

            <?php elseif (!empty($lot['winner_id']) && (strtotime($lot['date_end']) < strtotime('now'))): ?>

            <div class="timer timer--win">Ставка выиграла</div>


            <?php else: ?>
            <div class="timer timer--finishing"><?= time_interval($lot['date_end']) ?></div>
            <?php endif; ?>

            <?php else: ?> 
            <div class="timer"><?= time_interval($lot['date_end']) ?></div>
            
            <?php endif; ?>



          </td>
          <td class="rates__price">
           <?= price_format(htmlspecialchars($lot['max_price'])) ?>
         </td>

         <?php if ((strtotime('now')-strtotime(htmlspecialchars($lot['time'])))<'60'): ?>
         <td class="rates__time"><?= nounEnding(htmlspecialchars($lot['time']), 
          ["только что", "только что", "только что"]) ?></td>

          <?php elseif ((strtotime('now')-strtotime(htmlspecialchars($lot['time'])) >='60') && 
          (strtotime('now')-strtotime(htmlspecialchars($lot['time'])) <'3600')): ?>
          <td class="rates__time"><?= 
            (floor((strtotime('now')-strtotime(htmlspecialchars($lot['time'])))/'60')) .' '.
            nounEnding(floor((strtotime('now')-strtotime(htmlspecialchars($lot['time'])))/'60'), 
            ["минута назад", "минуты назад", "минут назад"]) ?></td>

            <?php elseif ((strtotime('now')-strtotime(htmlspecialchars($lot['time'])) >='3600') && 
            (strtotime('now')-strtotime(htmlspecialchars($lot['time'])) <'86400')): ?>
            <td class="rates__time"><?= 
              (floor((strtotime('now')-strtotime(htmlspecialchars($lot['time'])))/'3600')) .' '.
              nounEnding(floor((strtotime('now')-strtotime(htmlspecialchars($lot['time'])))/'3600'), 
              ["час назад", "часа назад", "часов назад"]) ?></td>

              <?php else: ?>
              <td class="rates__time"><?= htmlspecialchars($lot['time2']) ?></td>

              <?php endif; ?>
            </tr>


            <?php endforeach ?>
          </table>
        </section>
     
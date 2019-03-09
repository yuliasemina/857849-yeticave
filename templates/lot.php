<?php 
$lot_price = $lot['max_price'] ?: $lot['price'];
$min_bet = $lot_price + $lot['bet_step'];

$isbet=false;
foreach ($bets as $bet){
  if(($bet['bet_user_id']===$user_id) && ($bet['bet_lot_id']===$lot['id'])){
    $isbet=true;
  };
};

$user_id = null;
if (isset($_SESSION['user'])) {
  $user = $_SESSION['user']; 
  $user_id = $user['id'];

} 

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
      <section class="lot-item container">
        <h2><?= htmlspecialchars($lot['title']) ?></h2>
        <div class="lot-item__content">
          <div class="lot-item__left">
            <div class="lot-item__image">
              <img src="<?= htmlspecialchars($lot['image']) ?>" width="730" height="548" alt="<?= htmlspecialchars($lot['title']) ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category_name']) ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($lot['description']) ?></p>
          </div>
          <div class="lot-item__right">

            <div class="lot-item__state">
              <div class="lot-item__timer timer">
                <?= time_interval($lot['date_end']); ?>
              </div>
              <div class="lot-item__cost-state">
                <div class="lot-item__rate">
                  <span class="lot-item__amount">Текущая цена</span>
                  <span class="lot-item__cost">
                    <?= (price_format($lot_price)) ?>
                  </span>
                </div>
                <div class="lot-item__min-cost">
                  Мин. ставка <span>
                    <?= (price_format($min_bet)) ?>
                  </span>
                </div>
              </div>
              <?php if ((isset($user_id)) && ($lot['user_id'] != $user_id) 
              && (strtotime(($lot['date_end'])) > strtotime('now')) && !$isbet): ?>

            <form class="lot-item__form" action="lot.php?id=<?= ($lot['id']) ?>" method="post">
              <p class="lot-item__form-item form__item <?= count($errors) > 0 ? "form__item--invalid" : "";  ?>                  
              <label for="cost">Ваша ставка</label>
              <input id="cost" 
              type="text" 
              name="sum_bets" 
              placeholder="<?= (price_format($min_bet)) ?>"
              > 
              <span class="form__error isset($errors['sum_bets']) ? "form__item--invalid" : """>
                <?= $errors['sum_bets'] ?? "" ?>
              </span>
            </p>
            <button type="submit" class="button">Сделать ставку</button>
          </form>
        <?php endif; ?>
      </div>
      <div class="history">
        <h3>История ставок (<span><?php echo count($bets) ?></span>)</h3>
        <table class="history__list">
          <?php foreach ($bets as $bet): ?>
            <tr class="history__item">
              <td class="history__name"><?= htmlspecialchars($bet['user_name']) ?></td>
              <td class="history__price"><?= price_format($bet['sum_bets']) ?></td>

              <?php if ((strtotime('now')-strtotime(htmlspecialchars($bet['time'])))<'60'): ?>
              <td class="history__time"><?= nounEnding(htmlspecialchars($bet['time']), 
              ["только что", "только что", "только что"]) ?></td>

              <?php elseif ((strtotime('now')-strtotime(htmlspecialchars($bet['time'])) >='60') && 
              (strtotime('now')-strtotime(htmlspecialchars($bet['time'])) <'3600')): ?>
              <td class="history__time"><?= 
              (floor((strtotime('now')-strtotime(htmlspecialchars($bet['time'])))/'60')) .' '.
              nounEnding(floor((strtotime('now')-strtotime(htmlspecialchars($bet['time'])))/'60'), 
              ["минута назад", "минуты назад", "минут назад"]) ?></td>

              <?php elseif ((strtotime('now')-strtotime(htmlspecialchars($bet['time'])) >='3600') && 
              (strtotime('now')-strtotime(htmlspecialchars($bet['time'])) <'86400')): ?>
              <td class="history__time"><?= 
              (floor((strtotime('now')-strtotime(htmlspecialchars($bet['time'])))/'3600')) .' '.
              nounEnding(floor((strtotime('now')-strtotime(htmlspecialchars($bet['time'])))/'3600'), 
              ["час назад", "часа назад", "часов назад"]) ?></td>

              <?php else: ?>
                <td class="history__time"><?= htmlspecialchars($bet['time2']) ?></td>

              <?php endif; ?>
            </tr>
          <?php endforeach ?>
        </table>
      </div>
    </div>
  </div>

</section>
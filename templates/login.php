    <nav class="nav">
      <ul class="nav__list container">
         <?php foreach ($categories as $category): ?>
           <li class="nav__item">
            <a href="all_lots.php?id=<?= intval($category['id']) ?>"><?= htmlspecialchars($category['category_name']) ?></a>
          </li>
        <?php endforeach ?>
      </ul>
    </nav>
    <form class="form container <?= isset($errors) ? "form--invalid" : "" ?>" action="login.php" method="post"> 
      <h2>Вход</h2>
      <div class="form__item <?= isset($errors['email']) ? "form__item--invalid" : "" ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''?>" placeholder="Введите e-mail" required>
        <span class="form__error">
              <?= $errors['email'] ?? "" ?>
        </span>
      </div>
      <div class="form__item form__item--last <?= isset($errors['password']) ? "form__item--invalid" : "" ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" required>
         <span class="form__error">
              <?= $errors['password'] ?? "" ?>
        </span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>


Hi <?= $model->first_name ?>,<br>
<br>
<br>
We received a request to reset the password for your <?= Yii::$app->id ?> account
<br>
<br>
<br>
If you made this request, click the link below. If you didn’t make this request, you can ignore this email.<br>
<br>
<a href="http://<?= Yii::$app->params['siteUrl'] ?>/account/password-recovery/<?= $model->hash ?>"
   style="text-decoration: none;">
    http://<?= Yii::$app->params['siteUrl'] ?>/account/password-recovery/<?= $model->hash ?>
</a><br>
<br>
<br>
<?= Yii::$app->id ?> team
<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title				   = 'User Manage';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if(Yii::$app->session->hasFlash('error') || Yii::$app->session->hasFlash('success')): ?>
<div class="row">
	<div class="col-sm-12">
		<?php if(Yii::$app->session->hasFlash('error')): ?>
			<p class="bg-danger"><?=Yii::$app->session->getFlash('error')?></p>
		<?php elseif(Yii::$app->session->hasFlash('success')): ?>
			<p class="bg-success"><?=Yii::$app->session->getFlash('success')?></p>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>

<div class="candidate-level-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model'=>$searchModel]); ?>
	<p>
		<?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?php Pjax::begin(); ?>	
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			'username',
			'name',
			'email',
			[
				'class' => 'yii\grid\ActionColumn',
				'header' => 'Options',
			],
		],
	]); ?>
	<?php Pjax::end(); ?>

</div>
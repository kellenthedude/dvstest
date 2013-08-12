<h2>Experiment List:</h2><a href="/experiment/create/">Create New Experiment</a>
<?php foreach ($experiments as $exp): ?>

    <div id="main">
        <h3><a href="/experiment/<?php echo $exp['id'] ?>"><?php echo $exp['name'] ?></a></h3><a href="/experiment/delete/<?php echo $exp['id'] ?>">delete</a>
        
    </div>

<?php endforeach ?>
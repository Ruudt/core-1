
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<?php if($this->empty): ?>
<p class="message empty"><?php echo $this->message; ?></p>
<?php else: ?>
<?php echo $this->favorites; ?>
<?php endif; ?>

</div>
<!-- indexer::continue -->
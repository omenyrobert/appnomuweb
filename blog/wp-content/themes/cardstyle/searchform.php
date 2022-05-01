<form method="get" class="searchform themeform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<input type="text" class="search" name="s" onblur="if(this.value=='')this.value='<?php esc_attr_e('To search type and hit enter','cardstyle'); ?>';" onfocus="if(this.value=='<?php esc_attr_e('To search type and hit enter','cardstyle'); ?>')this.value='';" value="<?php esc_attr_e('To search type and hit enter','cardstyle'); ?>" />
	</div>
</form>
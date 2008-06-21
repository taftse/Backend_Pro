<table width="100%" cellspacing="0">
	<thead>
		<tr><th width="50%"><?=$this->lang->line('dashboard_statistics_name') ?></th><th><?=$this->lang->line('dashboard_statistics_value') ?></th></tr>
	</thead>
	
	<tbody>
		<tr><td><?=$this->lang->line('dashboard_statistics_system_status') ?></td><td><?=$system_status ?></td></tr>
		<tr><td><?=$this->lang->line('dashboard_statistics_total_members') ?></td><td><?=$total_members ?></td></tr>
		<tr><td><?=$this->lang->line('dashboard_statistics_total_unactivated_members') ?></td><td><?=$total_unactivated_members ?></td></tr>
		<tr><td><?=$this->lang->line('dashboard_statistics_user_registration') ?></td><td><?=$user_registration ?></td></tr>
		<tr><td><?=$this->lang->line('dashboard_statistics_version') ?></td><td><?=BEP_VERSION ?></td></tr>
	</tbody>
</table>
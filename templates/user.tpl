{**
 * Project:     Seeker
 * File:        user.tpl
 *
 * Seeker is free software: you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published 
 * by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * 
 * Seeker is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Seeker.  If not, see 
 * http://www.gnu.org/licenses/.
 *
 * @link http://code.google.com/p/seeker-game/
 * @copyright 2010 Speed School Student Council
 * @author Jared Hatfield
 * @package seeker-game
 * @version 1.0
 *}
{include file="header.tpl" title=Seeker xfn=$xfn}

<h2>{$fullname}</h2>


{if $contract_summary|@count > 0}
<h2>Contract Summary</h2>
<table class="data">
	<tr class="theading">
		<td style="width:300px;">Target Name</td>
		<td>Successful</td>
		<td>Expired</td>
		<td>Failed</td>
		<td>Missed</td>
		<td>Total</td>
	</tr>
{section name=mysec loop=$contract_summary}
	<tr>
		{strip}
		<td>
			{if $contract_summary[mysec].success > 0}
				<a href="{$baseurl}user/{$contract_summary[mysec].target}/" rel="met">{$contract_summary[mysec].name}</a>
			{else}
				<a href="{$baseurl}user/{$contract_summary[mysec].target}/" >{$contract_summary[mysec].name}</a>
			{/if}
		</td>
		{/strip}
		<td style="text-align:center;">{$contract_summary[mysec].success}</td>
		<td style="text-align:center;">{$contract_summary[mysec].expired}</td>
		<td style="text-align:center;">{$contract_summary[mysec].failed}</td>
		<td style="text-align:center;">{$contract_summary[mysec].missed}</td>
		<td style="text-align:center;">{$contract_summary[mysec].total}</td>
	</tr>
{/section}
</table>
<center><img src="{$past_contract_chart}" /></center>
<center>Contracts issued to {$fullname}</center>
<br />
<br />
{/if}


{if $enemies|@count > 0}
<h2>Seekers Summary</h2>
<table class="data">
	<tr class="theading">
		<td style="width:300px;">Seeker Name</td>
		<td>Successful</td>
		<td>Expired</td>
		<td>Failed</td>
		<td>Missed</td>
		<td>Total</td>
	</tr>
{section name=mysec loop=$enemies}
	<tr>
		{strip}
		<td>
			{if $enemies[mysec].success > 0}
				<a href="{$baseurl}user/{$enemies[mysec].assassin}/" rel="met">{$enemies[mysec].name}</a>
			{else}
				<a href="{$baseurl}user/{$enemies[mysec].assassin}/" >{$enemies[mysec].name}</a>
			{/if}
		</td>
		{/strip}
		<td style="text-align:center;">{$enemies[mysec].success}</td>
		<td style="text-align:center;">{$enemies[mysec].expired}</td>
		<td style="text-align:center;">{$enemies[mysec].failed}</td>
		<td style="text-align:center;">{$enemies[mysec].missed}</td>
		<td style="text-align:center;">{$enemies[mysec].total}</td>
	</tr>
{/section}
</table>
<center><img src="{$past_target_chart}" /></center>
<center>Contracts issued with {$fullname} as the target</center>
<br />
<br />
{/if}



{if $contracts|@count > 0}
<h2>Recent Contracts</h2>
<table class="data">
	<tr class="theading">
		<td style="width:180px;">Target</td>
		<td style="width:180px;">Assigned</td>
		<td style="width:180px;">Closed</td>
		<td>Status</td>
	</tr>
{section name=mysec loop=$contracts}
	<tr>
		{strip}
		<td>
			{if $contracts[mysec].status_name == "Succeeded"}
				<a href="{$baseurl}user/{$contracts[mysec].target}/" rel="met">{$contracts[mysec].target_name}</a>
			{else}
				<a href="{$baseurl}user/{$contracts[mysec].target}/" >{$contracts[mysec].target_name}</a>
			{/if}
		</td>
		{/strip}
		<td>{$contracts[mysec].assigned}</td>
		<td>{$contracts[mysec].updated}</td>
		<td>{$contracts[mysec].status_name}</td>
	</tr>
{/section}
</table>
	{if $contracts|@count == 30}
		<br />
		<center>Only showing the most recent 30 contracts</center>
	{/if}
{else}
This user has no contracts that have concluded.
{/if}
<br />

{include file="footer.tpl"}

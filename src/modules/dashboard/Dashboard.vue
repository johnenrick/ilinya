<template>
<div>
	<div align="center">
	welcome
	<table>
	<tr>

	<bars :data="[50, 10, 40, 10,5,6,6,6,6,6]"
		  :gradient="['#6fa8dc', '#42b983']"
		  :growDuration="1">
	</bars>
	</tr>
	<tr>
	</tr>
 </table>
 </div>
 </div>
</template>
<script>
	import Vue from 'vue'
	import Bars from 'vuebars'

	Vue.use(Bars)

</script>
<style scoped>
	
</style>

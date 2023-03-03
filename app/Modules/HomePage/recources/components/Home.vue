<template>
    <div class="container" style="background-color: #f0f0f0; height: 100vh;">
<!--    <NavBar></NavBar>-->
    <SearchCities></SearchCities>
    </div>

</template>

<script>
import NavBar from "./NavBar";
import SearchCities from "../../../SearchCities/resources/components/SearchCities";

export default {
    name: "Home",
    components: {
        NavBar,
        SearchCities,
    }
}
</script>

<style scoped>

</style>

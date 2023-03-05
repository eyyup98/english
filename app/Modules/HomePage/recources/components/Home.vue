<template>
    <NavBar></NavBar>
<!--    <div class="container" style="background-color: #f0f0f0; height: 100vh;">-->
    <LearnWords></LearnWords>
<!--    <SearchCities></SearchCities>-->
<!--    </div>-->
</template>

<script>
import NavBar from "./NavBar";
import SearchCities from "../../../SearchCities/resources/components/SearchCities";
import LearnWords from "../../../Words/resources/components/LearnWords";

export default {
    name: "Home",
    components: {
        NavBar,
        SearchCities,
        LearnWords
    }
}
</script>

<style scoped>

</style>

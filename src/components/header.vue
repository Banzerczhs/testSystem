<template>
  <el-header class="header-wrap">
    <h1>学生考试系统</h1>
  </el-header>
</template>

<script>
// import {Container} from "element-ui";

export default {
  name: 'HelloWorld',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  }
}
</script>

<style scoped lang="less">
  .header-wrap{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    box-shadow: 0 1px 10px 0px rgba(128, 121, 99, 0.5);
    h1{
      color: rgb(116, 111, 110);
      font-size: 24px;
    }
  }
</style>


<!-- Add "scoped" attribute to limit CSS to this component only -->

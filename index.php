<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./jquery.min.js"></script>
    <script src="./vue.min.js"></script>
    <link href="./bootstrap3.min.css" rel="stylesheet">
    <title>每日归寝打卡</title>
    <style>
    .panel-body{
        padding:6px;
    }
    #app{
        max-width: 500px;
    }
    </style>
</head>

<body>
    <div id="app" class="container-fluid">
        <h2>每日打卡-{{date}}</h2>
        <div id="selectbox" class="panel panel-default">
            <div id="banji" class="panel-body">
                选择班级:
                    <select name="class" id="class" >
                        <option value="17国金">17国金</option>
                    </select>
            </div>
            <div id="sushe" class="panel-body">
                选择宿舍:
                    <select name="dormcode" id="dormcode" @change="change()" >
                        <option value="">请选择</option>
                        <option :value="item" v-for="(item,index) in dorms">{{item}}</option>
                    </select>
            </div>
        </div>
        <div id="dakaselectlist" v-if="names" class="">
                <h5 v-if="loading" id="loading">加载中...</h5>
                <div id="checklist" v-for="(name,index) in names" class="panel-body">
                    <div>{{name}}</div>
                    <div>
                        <input type="radio" :name="'sushe'+index" id="s1" :value="name" @change="fguisu(name,1)">
                        归寝
                        </input>
                        <input type="radio" :name="'sushe'+index" id="s2" :value="name" @change="fguisu(name,2)">
                        晚归平安
                        </input>
                        <input type="radio" :name="'sushe'+index" id="s3" :value="name" @change="fguisu(name,3)">
                        回家
                        </input>
                    </div>
                    <div>
                        <input type="radio" :name="'jiankang'+index" id="j1" :value="name" @change="fjiankang(name,1)">
                        已打卡
                        </input>
                        <input type="radio" :name="'jiankang'+index" id="j2" :value="name" @change="fjiankang(name,0)">
                        未打卡
                        </input>
                    </div>
                </div>
        
        </div>
        <div id="hiddeninput" style="display:none">
            <input type="text" id="guisu" v-model="guisu">
            <input type="text" id="jiankangdaka" v-model="jiankangdaka">
            <input type="text" id="copy">>
        </div>
        <div class="alert alert-success" role="alert" id="messages" style="display:none"></div>
        <div>
            <div class="btn-group" role="group" aria-label="...">
                <button id="submit" @click="submit" :disabled="disabled" class="btn btn-primary">{{text}}</button>
                <button @click="capture" class="btn btn-warning">截图</button> 
                <button @click="search" class="btn btn-success">复制</button> 
                <button @click="yesterday" class="btn btn-info">昨日</button> 
            </div>
        </div>
        <div id="html2canvas" style="background-color:white">
            <h3>{{date}}打卡情况</h3>
            <div>宿舍数{{dorms.length}}，已经打卡宿舍数{{display ? display.length : 0}}</div>
            <div>
                <table class="table table-striped table-condensed " >
                    <tr>
                        <th>宿舍号</th>
                        <th>打卡情况</th>
                    </tr>
                    <tr v-for="item in display">
                        <td>{{item.dormcode}}</td>
                        <td>{{item.content}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <script>
    var app = new Vue({
        el: '#app',
        data: {
            date:"",
            dorms: [],
            names: [],
            guisu: new Map(),
            jiankangdaka: new Map(),
            dormcode: '',
            content:"",
            display:[],
            disabled:false,
            text:"提交打卡信息",
            loading:false
        },
        created: function() {
            $.ajax({
                url: "function.php", // 发送的路径
                type: "GET", // 发送方式
                data: {
                    class: '17国金'
                },
                success: (res) => { // 成功获取到后端返回结果的回调函数
                    //console.log(res)
                    this.date = '<?php echo date("Y-m-d");?>'
                    this.dorms = $.parseJSON(res)
                    //console.log(this.dorms)
                },
                error: (e) => { // 发送失败的回调函数
                    console.log("失败" + e);
                }
            })
        },
        mounted:function(){
            $.ajax({
                url: "function.php", // 发送的路径
                type: "GET", // 发送方式
                data: {
                    display:true
                },
                success: (res) => { // 成功获取到后端返回结果的回调函数
                    console.log($.parseJSON(res))
                    this.display = $.parseJSON(res)
                    //console.log(this.dorms)
                },
                error: (e) => { // 发送失败的回调函数
                    console.log("失败" + e);
                }
            })
        },
        methods: {
            getVal(value) {
                console.log(value)
            },
            change(value) {
                var dormcode = $("#dormcode").val()
                /**
                * radio常见操作,用于刷新选中状态，让新渲染的项目进入map表
                * document.querySelectorAll('.class、#id')与document.getElementsByName('name值')能达到一样的效果，代码将用前者造轮子
                * 缺点：操作需要循环去定位然后在操作
                *
                */
                var radioObj = document.querySelectorAll('input[type=radio]');
                for(var i = 0;i < radioObj.length;i++){
                    if(radioObj[i].checked == true){
                        //console.log(radioObj[i].value);//获取选中的值
                        radioObj[i].checked = false;//设置取消选中
                    }else{
                    //radioObj[i].checked = true;//设置选中
                    }
                }
                console.log(dormcode)
                this.loading = true
                $.ajax({
                    url: "function.php", // 发送的路径
                    type: "GET", // 发送方式
                    data: {
                        dormcode: dormcode
                    },
                    success: (res) => { // 成功获取到后端返回结果的回调函数
                        $("#dakaselectlist").addClass("panel panel-default")
                        console.log(res)
                        //$("#dakaselectlist").html("")
                        this.names = $.parseJSON(res)
                        this.dormcode = dormcode
                        
                        this.guisu.clear()
                        this.jiankangdaka.clear()
                        this.loading = false
                        console.log(this.names)
                    },
                    error: (e) => { // 发送失败的回调函数
                        console.log("失败" + e);
                        $("#loading").text("加载失败请重试！")
                    }
                })
            },
            fguisu(e, i) {
                console.log(e + i)
                this.guisu.set(e, i)
            },
            fjiankang(e, i) {
                console.log(e + i)
                this.jiankangdaka.set(e, i)
            },
            submit() {
                var huijiaderen = ""
                var wanguideren = ""
                var content1 = ""
                var daka = ""
                var meidaka = ""
                var content2 = ""
                if (!this.dormcode) {
                    alert("请先选择宿舍")
                    return 0
                }
                console.log("该宿舍为" + this.dormcode)
                console.log("宿舍人数为" + this.names.length)
                console.log("填写归寝情况人数为" + this.guisu.size)
                console.log("填写健康打卡情况人数为" + this.jiankangdaka.size)
                if (this.names.length != this.guisu.size || this.names.length != this.jiankangdaka.size) {
                    alert("请全部填写完整再提交")
                    return 0
                } else {
                    //提交锁
                    this.disabled = true
                    this.text = "上传中"
                    this.guisu.forEach((value, key, map)=> {
                        console.log(`g[${key}] = ${value}`);
                        switch (`${value}`) {
                            case `2`:
                                wanguideren = wanguideren + `${key}`
                            break
                            case `3`:
                                huijiaderen = huijiaderen + `${key}`
                            break
                        }
                    });
                    this.jiankangdaka.forEach((value, key, map)=> {
                        console.log(`j[${key}] = ${value}`);
                        switch (`${value}`) {
                            case `0`:
                                meidaka = meidaka + `${key}`
                            break
                            case `1`:
                                daka = daka + `${key}`
                            break
                        }
                    });
                    if(wanguideren){
                            wanguideren = wanguideren + "晚归 "
                            if(huijiaderen){
                                huijiaderen = huijiaderen + "回家"
                            }
                        }else if(huijiaderen){
                            huijiaderen = huijiaderen + "回家"
                        }else{
                            content1 = "全体归寝"
                        }
                         
                    if(meidaka){
                        meidaka = meidaka + "未打卡"
                    }else{
                        content2 = "全体打卡"
                    }
                    this.content = wanguideren + huijiaderen + content1 + ","+meidaka+ content2
                    console.log(this.content)

                    $.ajax({
                    url: "function.php", // 发送的路径
                    type: "GET", // 发送方式
                    data: {
                        dorm: this.dormcode,
                        date:this.date,
                        content:this.content
                    },
                    success: (res) => { // 成功获取到后端返回结果的回调函数
                        console.log(res)
                        $("#messages").text("上传成功！")
                        $("#messages").show().delay(3000).hide(300);
                        this.disabled = false
                        this.text = this.dormcode + "上传成功"
                    },
                    error: (e) => { // 发送失败的回调函数
                        console.log("失败" + e);
                    }
                })
                }
            },
            capture(e,i){
                const w = $('#html2canvas').outerWidth(),
                      h = $('#html2canvas').outerHeight();
                console.log(w,h)
                html2canvas($("#html2canvas"), {
                    allowTaint: true,
                    taintTest: false,
                    width: w,
                    height: h,
                    // window.devicePixelRatio是设备像素比
                    scale: window.devicePixelRatio,
                    onrendered: function(canvas) {
                        const dataUrl = canvas.toDataURL("image/png", 1.0),
                              newImg = document.createElement("img");
                        newImg.src = dataUrl;
                        document.body.appendChild(newImg)
                        newImg.style.width = '100%';
                        $("img").css({"text-align":"center","padding":"15px"})
                        $("img").addClass("panel-body")
                        $("#html2canvas").toggle()
                        $("#messages").text("截图生成成功！")
                        $("#messages").show().delay(1500).hide(300);
                    }
                });
            },
            search(e,i){
                
                var list = [] //提交的宿舍
                    dorms = Array.from(this.dorms)
                for(var i = 0 ;i<this.display.length;i++){
                    list.push(this.display[i].dormcode)
                }
                //console.log(list)
                //console.log(dorms)
                //取差集
                var chaji = list.concat(dorms).filter(v => !list.includes(v) || !dorms.includes(v))
                var clipBoardContent = chaji+" 记得报下归寝哈"
                
                const input = document.createElement('input');
                document.body.appendChild(input);
                input.setAttribute('value', clipBoardContent);
                input.select();
                if (document.execCommand('copy')) {
                    document.execCommand('copy');
                    console.log('复制成功');
                    $("#messages").text("归寝信息复制成功！")
                    $("#messages").show().delay(1500).hide(300);
                }
                document.body.removeChild(input);
                console.log(chaji+" 记得报下归寝哈")
            },
            yesterday(e,i){
                $.ajax({
                url: "function.php", // 发送的路径
                type: "GET", // 发送方式
                data: {
                    yesterday:true
                },
                success: (res) => { // 成功获取到后端返回结果的回调函数
                    console.log($.parseJSON(res))
                    this.display = $.parseJSON(res)
                    this.date = '<?php echo date("Y-m-d",strtotime("-1 day"));?>'
                    //console.log(this.dorms)
                    $("#messages").text("显示昨日归寝情况")
                    $("#messages").show().delay(1500).hide(300);
                },
                error: (e) => { // 发送失败的回调函数
                    console.log("失败" + e);
                }
            })
            }
        }
    })
    </script>
    <script src="./html2canvas.js"></script>
</body>

</html>
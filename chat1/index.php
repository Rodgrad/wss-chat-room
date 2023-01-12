<script src="https://cdn.tailwindcss.com"></script>


<body class="container mx-auto">

    <h1 class="text-orange text-6xl font-black">TeamServer 1</h1>

    <div class="grid grid-cols-8">


        <div class="col-span-2 rounded shadow min-h-96 w-full p-4">
            <div>
                <label class=""for="user">Enter your ID</label>
                <input class="w-full shadow border " type="text" id="user" value="Guest">
            </div>
            <div>
                <label class="" for="client">Select Group:</label>
                <select class="w-full shadow border" name="group" id="group" multiple>
                    <option value="room1" selected >Room 1</option>
                    <option value="room2">Room 2</option>
                    <option value="room3">Room 3</option>
                </select>
                <button onclick="connect_users()" class="rounded shadow p-2 text-white bg-orange border mt-4">
                    Connect
                </button>
                <p id="connection-status" class="text-green font-black text-center"></p>
            </div>
        </div>


        <div class="col-span-6 rounded shadow min-h-96 w-full  mt-4 p-4" >
            <h3 class="text-center text-blue font-black ">Messages</h3>
            <div id="message-bucket" class="w-full h-96 shadow border overflow-scroll"></div>
            <textarea class="w-full h-24 shadow border" name="message" id="message" placeholder="...say something."></textarea>
            <button onclick="send_data()" class="rounded bg-green p-2 shadow text-white float-right mt-4" id="send">
                Send
            </button>
        </div>
    </div>






    <script>

        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            document.getElementById("connection-status").innerHTML = "Connected";
        };

        conn.onmessage = function(e) {
            data = JSON.parse(e.data);
            document.getElementById("message-bucket").innerHTML += "<p class='mr-2 ml-2 rounded p-2 text-sm bg-blue shadow mt-3 border'>" 
            + data["message"] 
            + "<br> from user "
            + "<small>" + data["user_id"]
            + "</small></p>";
            
        };



        function get_data(command){
            let user = document.getElementById("user").value;
            let group = document.getElementById("group").value;
            let message = document.getElementById("message").value;
            if(message == null || message == ""){
                message = "<small>Just Joined</small>";
            }
            return JSON.stringify({"user_id":user, "group":group, "message":message, "command":command});

        }


        function connect_users(){
            conn.send(get_data("client"));
        }

        function send_data(){
            conn.send(get_data("message"));
            document.getElementById("message").value = "";


        }
    </script>

    <script>
    tailwind.config = {
        theme: {
      
      section_height:{
        '1000px': '100',
      },
      screens: {
          'sm': '640px',
          // => @media (min-width: 640px) { ... }
    
          'md': '768px',
          // => @media (min-width: 768px) { ... }
    
          'lg': '1024px',
          // => @media (min-width: 1024px) { ... }
    
          'xl': '1280px',
          // => @media (min-width: 1280px) { ... }
    
          '2xl': '1536px',
          // => @media (min-width: 1536px) { ... }
      },
      colors: {
        'blue':     '#1fb6ff',
        'purple':   '#7e5bef',
        'pink':     '#ff49db',
        'orange':   '#ff7849',
        'green':    '#13ce66',
        'yellow':   '#ffc82c',
        'gray-dark': '#273444',
        'gray':     '#8492a6',
        'gray-light': '#f3f4f6',
        'green':    '#22c55e',
        'black':    '#171717',
        'slate':    '#0f172a',
        'sky-400':  '#38bdf8',
        'white':    '#ffffff',
      },
      fontFamily: {
        sans: ['Graphik', 'sans-serif'],
        serif: ['Merriweather', 'serif'],
      },
      extend: {
        spacing: {
          '128': '32rem',
          '144': '36rem',
        },
        borderRadius: {
          '4xl': '2rem',
        }
      }
        }}
    </script>
</body>




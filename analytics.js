(function(window){
    'use strict';

    function define_analytics(){
        var Analytics = {};
        var api = "//analytics.interactivemechanics.com/api/v1/events";

        var ProjectName;
        var InstanceID;
        var SessionScreenWidth;
        var SessionScreenHeight;
        var SessionID;
        
        Analytics.init = function(id){
            var split = id.split(/\s*\-\s*/g);
            ProjectName = split[0];
            InstanceID = split[1];

            SessionScreenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            SessionScreenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

            Analytics.bindEventListeners();
        }

        Analytics.bindEventListeners = function(){
            // TODO: Add more event listeners

            window.addEventListener("click", function(event){
                var data = {
                    "EventX": event.screenX,
                    "EventY": event.screenY,
                    "EventTarget": Analytics.generateEventTarget(event.target),
                    "EventType": event.type
                };
                Analytics.buildData(data);
            });
        }
        Analytics.generateEventTarget = function(el){
            var target = el.nodeName.toLowerCase();
            if (el.hasAttribute('id') && el.id != ''){
                target += '#' + el.id;
            }
            if (el.hasAttribute('className') && el.className != ''){
                // TODO: className not getting added
                target += '.' + el.className;
            }
            return target;
        }

        Analytics.startNewSession = function(){
            // TODO: Start a new session upon first interaction
            //   then set the SessionID variable
        }
        Analytics.terminateSession = function(){
            // TODO: Clear out session variable
        }

        Analytics.buildData = function(data){
            var json = {};
            var EventTimestamp = Date.now();

            json["EventTimestamp"] = EventTimestamp;
            json["ProjectName"] = ProjectName;
            json["InstanceID"] = InstanceID;
            json["SessionScreenHeight"] = SessionScreenHeight;
            json["SessionScreenWidth"] = SessionScreenWidth;

            for (var key in data) {
                json[key] = data[key];
            }

            Analytics.postRequest(JSON.stringify(json));
        }

        Analytics.postRequest = function(json){
            var xhr = new XMLHttpRequest();

            xhr.open("POST", api);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onload = function() {
                if (xhr.status !== 200) {
                    // TODO: Store locally, try again later
                }
            };
            xhr.send(json);
            console.log(json);
        }
    
        return Analytics;
    }

    if(typeof(Analytics) === "undefined"){
        window.Analytics = define_analytics();
    }
})(window);

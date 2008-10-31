Ext.namespace('game', 'game.chat', 'game.login', 'game.signon', 
              'game.channel', 'game.meebo');
game.prepared = false;
game.login.prepared = false;
game.signon.prepared = false;
game.channel.prepared = false;

game.meebo.tpl = new Ext.XTemplate(
    '<div style="width:{w}px; margin:2px auto">',
    '  <object width="{w}" height="{h}">',
    '    <param name="movie" value="http://widget.meebo.com/mcr.swf?id={widget}"></param>',
    '    <embed src="http://widget.meebo.com/mcr.swf?id={widget}"',
    '      type="application/x-shockwave-flash" width="{w}" height="{h}" />',
    '  </object>',
    '  <a href="http://www.meebo.com/rooms" class="mcrmeebo">',
    '  <img alt="http://www.meebo.com/rooms" src="http://widget.meebo.com/b.gif"',
    '      width="{w}" height="45" style="border:0px"/>',
    '  </a>',
    '</div>'); 

Ext.apply(Ext.form.VTypes, {
  password: function(val, field) {
    if (field.initialPassField) {
      var pwd = Ext.getCmp(field.initialPassField);
      return (val == pwd.getValue());
    }
    return true;
  },
  
  passwordText: 'Passwords do not match'
});

game.show_login_form = function(){
  if (!game.login.prepared){  
    var loginf = new Ext.FormPanel({
      id:  'login_form',
      url: 'login.php',
      labelWidth: 125,
      frame: true,
      bodyStyle:'padding:5px 5px 0',
      width: 400,
      cls: 'middle',
      defaults: {
        width: 175,
      },
      defaultType: 'textfield',
      items: [{
        fieldLabel: 'Username',
        name: 'username',
        id: 'login_username'
      },{
        fieldLabel: 'Password',
        name: 'pass',
        inputType: 'password',
        id: 'login_pass' // id of the initial password field
      }],

      buttons: [{
        text: 'Login',
        handler: function(){
          loginf.getForm().submit({
            url: 'login.php',
            /*waitMsg:'Saving Data...',*/
            success: function(response){
              game.chat.step = 'pick_channel';
              // Ext.destroy(Ext.getCmp('login_form'));
              Ext.get('login').replaceClass('step_active', 'step');
              Ext.get('authorized').setVisible(true);

              game.show_pick_channel_form();
            }});
        }
      }]    
      
    });

    loginf.render('logincnt');
    Ext.get('register').on('click', function(){
         game.chat.step = 'sign_on';
         Ext.get('login').replaceClass('step_active', 'step');

         game.show_registration_form();
    });
    game.login.prepared = true;
  }

  Ext.get('login').replaceClass('step', 'step_active');
  Ext.get('authorized').setVisible(false);
};

game.show_registration_form = function(){
  if (!game.signon.prepared){  
    var signonf = new Ext.FormPanel({
        labelWidth: 110, // label settings here cascade unless overridden
        url:'save_user.php',
        id: 'register_form',
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        cls: 'middle',
        width: 400,
        defaults: {width: 230},
        defaultType: 'textfield',

        items: [{
                fieldLabel: 'Username',
                name: 'username',
                allowBlank:false
            },{
                fieldLabel: 'Password',
                name: 'pass',
                id: 'pass',
                allowBlank:false,
                inputType: 'password'
            },{
                fieldLabel: 'Confirm Password',
                name: 'pass-cfrm',
                inputType: 'password',
                /*vtype: 'password',*/
                initialPassField: 'pass' // id of the initial password field
            },{
                fieldLabel: 'Email',
                name: 'email',
                allowBlank:false,
                vtype:'email'
            },{
                fieldLabel: 'Phone',
                name: 'phone'
            },{
                fieldLabel: 'Full Name',
                name: 'name'
            },{
                fieldLabel: 'Location',
                name: 'location'
            }, new Ext.form.DateField({
                fieldLabel: 'Date of Birth',
                name: 'birthday',
                width:190,
                allowBlank:false
            })
        ],

        buttons: [{
            text: 'Save',
            handler: function(){
              signonf.getForm().submit({
                url: 'save_user.php',
                /*waitMsg:'Saving Data...',*/
                success: function(response){
                  game.chat.step = 'pick_channel';
                  //Ext.destroy(Ext.getCmp('register_form'));
                  Ext.get('sign_on').replaceClass('step_active', 'step');

                  game.show_pick_channel_form();
                }});
            }
        },{
            text: 'Reset',
            handler: function(){
            }
        }]
    });

    signonf.render('signoncnt');  
    Ext.get('login_link').on('click', function(){
         game.chat.step = 'login';
         Ext.get('sign_on').replaceClass('step_active', 'step');
         game.show_login_form();
    });
    game.signon.prepared = true;
  }
  Ext.get('sign_on').replaceClass('step', 'step_active');
  Ext.get('authorized').setVisible(false);
};

game.show_pick_channel_form = function(){
  if (!game.channel.prepared){  
    var ds = new Ext.data.Store({  
        proxy: new Ext.data.HttpProxy({  
            // this json data contains only employees where active is 'true'  
            url: 'channel_combobox_data.php'  
        }),  
        reader: new Ext.data.JsonReader({
          idProperty:'node',
          root: 'data', 
          totalProperty: 'results',
          fields: [
              {name: 'node', type: 'string'},
              {name: 'room', type: 'string'},
              {name: 'desc', type: 'string'} 
          ]
        }),  
        remoteSort: true  
    });
    ds.load();

    var comboWithTooltip = new Ext.form.ComboBox({
        tpl: '<tpl for="."><div ext:qtip="{desc}" class="x-combo-list-item">{room}</div></tpl>',
        store: ds,
        id: 'chosen_channel',
        listClass: 'games_list',
        listWidth: 250, 
        valueField: 'node',
        displayField:'room',
        hiddenName: 'node',
        typeAhead: true,
        mode: 'remote',
        triggerAction: 'all',
        emptyText:'Select a channel...',
        selectOnFocus:true,
        applyTo: 'channels-with-qtip',
        listeners: {
          select:{
             fn: function(combo, value){
               game.meebo_node = value.data.node;
               game.meebo_room = value.data.room;
               JoinTalkBtn.show();
             }
          }/*,
          keydown:{
             fn: function(combo, value){
               JoinGameBtn.hide();
             }
          }*/
        }
    });
   
    var embed_meebo_widget = function(){
      var conn = new Ext.data.Connection();
      conn.request({
        url: 'embed_meebo_widget.php',
        method: 'POST',
        params: {meebo_node: game.meebo_node},
        success: function(response) {
          if (window.console){
            console.log(response.responseText);
          }
		  var json = Ext.decode(response.responseText);
          if (json.stat == 'ok'){
            Ext.get('pick_channel').replaceClass('step_active', 'step');
            game.show_meebo_widget(json.data);
          }
        },
        failure: function() {
          Ext.Msg.alert('Status', 'Unable to meebo widget. Please try again later.');
        }
      });
    }

    var JoinTalkBtn = new Ext.Button({
        text: 'Join Talk',
        renderTo: 'join_talk_btn',
        hidden: true,
        style: {display: "inline"},
        handler: function(){
          embed_meebo_widget();
        }
    });
    
    game.channel.prepared = true;
  }

  Ext.get('pick_channel').replaceClass('step', 'step_active');
  Ext.get('authorized').setVisible(true);
};

game.show_meebo_widget = function(meebo_data){
	game.meebo.tpl.overwrite('meebo_chat', meebo_data);
  Ext.get('pick_channel').replaceClass('step_active', 'step');
  Ext.get('meebo_chat').replaceClass('step', 'step_active');
}

game.show_pick_game_form = function(){
  if (!game.prepared){  
    var ds = new Ext.data.Store({  
      proxy: new Ext.data.HttpProxy({  
          // this json data contains only employees where active is 'true'  
          url: 'games_combobox_data.php'  
      }),  
      reader: new Ext.data.JsonReader({
        idProperty:'game_id',
        root: 'data', 
        totalProperty: 'results',
        fields: [
            {name: 'game_id', type: 'int'},
            {name: 'game_name', type: 'string'},
            {name: 'rules', type: 'string'},
            {name: 'min_players', type: 'int'},
            {name: 'max_players', type: 'int'}
        ]
      }),  
      remoteSort: false  
    });
    ds.load();

    var comboWithTooltip = new Ext.form.ComboBox({
        tpl: '<tpl for="."><div ext:qtip="{min_players}-{max_players}. {rules}" class="x-combo-list-item">{game_name}</div></tpl>',
        store: ds,
        id: 'chosen_game',
        listClass: 'games_list',
        listWidth: 250, 
        valueField: 'game_id',
        displayField:'game_name',
        hiddenName: 'game_id',
        typeAhead: true,
        mode: 'remote',
        triggerAction: 'all',
        emptyText:'Select a game...',
        selectOnFocus:true,
        applyTo: 'games-with-qtip',
        listeners: {
          select:{
             fn: function(combo, value){
               game.id = value.data.game_id;
               game.name = value.data.game_name;

               JoinGameBtn.show();
             }
          }/*,
          keydown:{
             fn: function(combo, value){
               JoinGameBtn.hide();
             }
          }*/
        }
    });

    var request_room = function(){
      var conn = new Ext.data.Connection();
      conn.request({
        url: 'game_assign_room.php',
        method: 'POST',
        params: {game_id: game.id, game_name: game.name},
        success: function(responseObject) {
          if (window.console){
            console.log(responseObject.responseText);
          }
          Ext.get('pick_game').replaceClass('step_active', 'step');
          show_chat();
        },
        failure: function() {
          Ext.Msg.alert('Status', 'Unable to request room. Please try again later.');
        }
      });
   };

   var JoinGameBtn = new Ext.Button({
      text: 'Join this Game',
      renderTo: 'start_btn',
      hidden: true,
      style: {display: "inline"},
      handler: function(){
        request_room();
      }
   });

   game.prepared = true;
  }
    
  Ext.get('pick_game').replaceClass('step', 'step_active');
  Ext.get('authorized').setVisible(true);
};

Ext.onReady(function(){

    Ext.QuickTips.init();
    // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.get('authorized').setVisibilityMode(Element.DISPLAY);

    Ext.get('logout').on('click', function(){
      var conn = new Ext.data.Connection();
      conn.request({
        url: 'logout.php',
        method: 'GET',
        success: function(responseObject) {
          if (window.console){
            console.log(responseObject.responseText);
          }
          game.chat.step = 'login';
          Ext.select('div.step_active').replaceClass('step_active', 'step');
          //Ext.get('sign_on').replaceClass('step_active', 'step');
          game.show_login_form();
        }
      });
    });

    if (game.chat.step == 'login') {
      game.show_login_form();
    }
    else if (game.chat.step == 'login') {
      game.show_registration_form();      
    }
    else if (game.chat.step == 'pick_game') {
      game.show_pick_game_form();
    }
    else if (game.chat.step == 'pick_channel') {
      game.show_pick_channel_form();
    }
    else {
      show_chat();
    }

});

		public function login(username:String, password:String):void
		{
			var session:Object = new Object();
			session['code'] = _sessionID;
			
			var loginInfo:Object = new Object();
			loginInfo['username'] = username;
			loginInfo['password'] = SHA1.encrypt(_authSalt+password);
			
			var everything:Object = new Object();
			everything['session_info'] = session;
			everything['login_method'] = "password";
			everything['login_blob'] = loginInfo;
			
			var responder:Responder = new Responder(onLogin, onNetworkingError);
			_conn.call("XCRpcProtocol_AMF.login", responder, session, "password", loginInfo);
		}
		


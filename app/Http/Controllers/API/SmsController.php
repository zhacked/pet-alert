<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {

        $this->middleware('auth:api');

    }
    public function index()
    {
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function smsSend(Request $request)
    {
       
//   dd(getenv("ITXTMO_CODE"));
        // $ch = curl_init();
		// $itexmo = array("1" => $request['clientNumber'], "2" => $request['message'], "3" => getenv("ITXTMO_CODE"), 'passwd' => getenv("ITXTMO_PASS"));
		// curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		// 	http_build_query($itexmo));
	    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// return curl_exec ($ch);
		// curl_close ($ch);

        $apiCode = getenv("ITXTMO_CODE");
        $apiPass = getenv("ITXTMO_PASS");

        $url = 'https://www.itexmo.com/php_api/api.php';
		$itexmo = array("1" => $request['clientNumber'], "2" => $request['message'], "3" => $apiCode, 'passwd' => $apiPass);

		$param = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($itexmo),
			),
		);
		$context  = stream_context_create($param);
		return file_get_contents($url, false, $context);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

   
}

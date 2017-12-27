<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
class homecontroller extends Controller
{

    public function index(){
        return view('home.index');
    }

    public function create()
    {
        $machines = machine::pluck('id', 'Machine_id');
        return view('machine.create',compact('machines'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'Machine_name' => 'required',
            'Purchase_date' => 'required',
            'Status' => 'required',
            'Machine_prices' => 'required',
            'Service_life' => 'required',
            'Instrument_sort' => 'required',
            'Purchasing_department' => 'required',
            'Manfaucturer' => 'required',
            'Model' => 'required',
            'id' => 'required'
        ]);
        machine::create($request->all());
        return redirect()->route('machine.index')
            ->with('success','新增成功');
    }

    public function show($id)
    {
        $machines =machine::find($id);
        return view('machine.show',compact('machines'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $machines= machine::find($id);
        return view('machine.edit',compact('machines'));
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
        $this->validate($request, [

            'Machine_name' => 'required',
            'Purchase_date' => 'required',
            'Status' => 'required',
            'Machine_prices' => 'required',
            'Service_life' => 'required',
            'Instrument_sort' => 'required',
            'Purchasing_department' => 'required',
            'Manfaucturer' => 'required',
            'Model' => 'required',
            'id' => 'required'
        ]);

        machine::find($id)->update($request->all());
        return redirect()->route('machine.index')
            ->with('success','更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $serverName = "163.17.9.113\SQLEXPRESS";
        $connectionInfo = array( "Database"=>"cc", "UID"=>"sa", "PWD"=>"s10314161", "CharacterSet"=>"UTF-8");
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        $sql="select*from DB_Machinelist where Machine_id=".$id;
        $result=sqlsrv_query($conn,$sql)or die("sql error".sqlsrv_errors());
        $array[]=0;
        while($row=sqlsrv_fetch_array($result)) {
            $array[0]=$row[0];
        }if($array[0]!=null){
        return redirect()->route('machine.index')
            ->with('success','已進入排程 無法刪除!');
    }else{
        machine::find($id)->delete();
        return redirect()->route('machine.index')
            ->with('success','刪除成功');
    }
    }
}


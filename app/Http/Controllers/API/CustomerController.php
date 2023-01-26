<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $busqueda = $request->input('filter');
        $pagina = $request->input('page');
        $numElementos = $pagina['size'];
        $numPagina = $pagina['number'];
        $request->merge(array('page' => $numPagina));
        $registrosCustomers =
            ($busqueda && array_key_exists('q', $busqueda))
            ? Customer::where('first_name', 'like', '%' .$busqueda['q'] . '%')
                ->paginate($numElementos)
            : Customer::paginate($numElementos);

            return CustomerResource::collection($registrosCustomers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = json_decode($request->getContent(), true);

        $customer = Customer::create($customer['data']['attributes']);

        return new CustomerResource($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $customerData = json_decode($request->getContent(), true);
        $customer->update($customerData['data']['attributes']);

        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
    }
}

<div>
    <h1>Appointment Record</h1>

    <p>Hello Mr/Mrs {{$client->name}}</p>

     <div>
         <p>We would like to inform you that your appointment <br>
            has been confirmed with Dr/Dra.{{$employee->name}} <br>
            that was scheduled on  the following :<br>   
            <br>
            <span>
                <strong> Schedule Details</strong>
            </span>
            <span>
                <ul >
                    <li>
                       <strong>Veterinarian :</strong>  Dr/Dra.{{$employee->name}}
                    </li>
                    <li>
                       <strong> Service : </strong> {{$service->name}}
                    </li>
                    <li>
                      <strong>Start Date :</strong> {{$date_start}}
                    </li>
                </ul>
            </span>
            <br>
            <span>
                <strong> Pet Details</strong>
            </span>
            <span>
                <ul >
                    <li>
                      <strong>Name :</strong>   {{$pet->Name}}
                    </li>
                    <li>
                       <strong> Type :</strong> {{$pet->species}}
                    </li>
                    <li>
                      <strong>  breed :</strong> {{$pet->breed}}
                    </li>
                    <li>
                       <strong> birthday : </strong> {{$pet->birthday}}
                    </li>
                </ul>
            </span>
        </p>
        <br>
        <p>
            If you need to cancel or reschedule please <br>
            reply to this email.
        </p>

        <p>
            Thankyou and keep safe
        </p>
        <h4>Pet Allert</h4>
     </div>
</div>

{{-- <table>
    <tr>
        <td>
            {{$client}}
        </td>
        <td>
            {{$employee}}
        </td>
        <td>
            {{$service}}
        </td>
        <td>
            {{$pet}}
        </td>
    </tr>
</table> --}}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitngDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'serial_number' => $this->id,
            'visit_registration_number' => $this->reg_no,
            'visit_purpose' =>$this->purpose,
            'name_of_company' => $this->company_name,
            'company_employee_id' => $this->company_employee_id,
            'checkin_time' => $this->checkin_at,
            'checkout_time' => $this->checkout_at,
            'status' => $this->status == 5 ? 'Active': 'Not Active',
            'users\'s_visit' => $this->user_id,
            'employee_created_this_visit' => $this->creator_employee,
            'emp_one' => $this->emp_one ,
            'emp_two' => $this->emp_two ,
            'visit\'s_type' => $this->type_id ,
            'visitor_serial_number' => $this->visitor_id ,
            'creator_type' => $this->creator_type ,
            'creator_serial_number' => $this->creator_id ,
            'editor_type' => $this->editor_type == NULL ? 'No One' : $this->editor_type,
            'editor_serial_number' => $this->editor_id == NULL ? 'No ID' : $this->editor_id ,
            'car_plate_number' => $this->plate_no ,
            'qrcode' => $this->qrcode ,
            'visit_expiration_date' => $this->expiry_date ,
            // 'visitor' => $this->visitor ,
            // 'visitor_serial_number' => $this->visitor->id ,
            'visitor_first_name' => $this->visitor->first_name ,
            'visitor_last_name' => $this->visitor->last_name ,
            'visitor_email' => $this->visitor->email ,
            'visitor_phone' => $this->visitor->phone ,
            'visitor_gender' => $this->visitor->gender == 10 ? 'Female' : 'Male',
            'visitor_address' => $this->visitor->address,
            'visitor_status' => $this->visitor->status == 5 ? 'Active' : 'Not Active',
            'visitor_creator_serial_number' => $this->visitor->creator_id,
            'visitor_editor_type' => $this->visitor->editor_type,
            'visitor_editor_serial_number' => $this->visitor->editor_id,
            'visitor_created_at' => $this->visitor->created_at,
            'visitor_updated_at' => $this->visitor->updated_at != NULL ? $this->visitor->updated_at : 'Not Edited Yet' ,
            'visitor_companions' => $this->companions,
//            'visitor_companions_first_name' => $this->first_name,
//            'visitor_companions_last_name' => $this->last_name,
//            'visitor_companions_national_id' => $this->national_id,
//            'visitor_companions_created_at' => $this->created_at,
        ];
        // return parent::toArray($request);
    }
}

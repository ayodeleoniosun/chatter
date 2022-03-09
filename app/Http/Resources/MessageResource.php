<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender_id'       => $this->sender_id,
            'sender'          => $this->sender->fullname,
            'created_at'      => $this->parseDate(),
        ];
    }

    public function parseDate(): string
    {
        $date = Carbon::parse($this->created_at);
        $time = $date->format("h:i A");

        if ($date->isToday()) {
            return $time;
        } elseif ($date->isYesterday()) {
            return "Yesterday, {$time}";
        } else {
            return $date->format("d/m/Y") . ", {$time}";
        }
    }
}

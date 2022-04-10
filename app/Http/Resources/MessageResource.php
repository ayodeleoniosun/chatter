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
            'is_read'         => $this->is_read,
            'read_at'         => $this->is_read ? $this->parseDate($this->read_at) : null,
            'created_at'      => $this->parseDate($this->created_at),
        ];
    }

    private function parseDate($date): string
    {
        $parsedDate = Carbon::parse($date);
        $time = $parsedDate->format('h:i A');

        if ($parsedDate->isToday()) {
            return $time;
        } elseif ($parsedDate->isYesterday()) {
            return "Yesterday, {$time}";
        }

        return $parsedDate->format('d/m/Y') . ", {$time}";
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Message;
use App\Repositories\MessageRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $authUserId = $request->user()->id;
        $showName = ($authUserId == $this->sender_id) ? $this->recipient->fullname : $this->sender->fullname;

        return [
            'id'                    => $this->id,
            'sender_id'             => $this->sender_id,
            'recipient_id'          => $this->recipient_id,
            'sender'                => $this->sender->fullname,
            'recipient'             => $this->recipient->fullname,
            'show_name'             => $showName,
            'last_message'          => new MessageResource($this->messages()->latest('id')->first()),
            'count_unread_messages' => app(MessageRepository::class)->getConversationUnreadMessages($authUserId, $this->id)->count(),
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}

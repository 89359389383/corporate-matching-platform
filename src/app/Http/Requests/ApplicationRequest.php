<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認可チェック（true のため、このリクエストは誰でも送信可能。必要ならログイン必須などに変更）
        return true;
    }

    public function rules(): array
    {
        return [
            // 応募時に送るメッセージ本文（必須・文字列）
            'message' => ['required', 'string', 'max:2000'],

            // 希望時間単価（円/時間）
            'desired_hourly_rate' => ['required', 'integer', 'min:0', 'max:10000000'],

            // 稼働曜日（目安）
            'work_days' => ['required', 'array', 'min:1'],
            'work_days.*' => ['string', 'in:月,火,水,木,金,土,日'],

            // 稼働時間帯（目安）
            'work_time_from' => ['required', 'date_format:H:i'],
            'work_time_to' => ['required', 'date_format:H:i', 'after:work_time_from'],

            // 備考
            'note' => ['nullable', 'string', 'max:2000'],

            // 合計週稼働時間（目安）
            'weekly_hours' => ['required', 'integer', 'in:5,10,20,30,40'],

            // 開始可能日
            'available_start' => ['required', 'string', 'in:即日,2週間後,1ヶ月後,3ヶ月後以降'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => '応募メッセージを入力してください。',
            'message.string' => '応募メッセージは文字列で入力してください。',
            'message.max' => '応募メッセージは2000文字以内で入力してください。',

            'desired_hourly_rate.required' => '希望時間単価を入力してください。',
            'desired_hourly_rate.integer' => '希望時間単価は数値で入力してください。',
            'desired_hourly_rate.min' => '希望時間単価は0以上で入力してください。',
            'desired_hourly_rate.max' => '希望時間単価が大きすぎます。',

            'work_days.required' => '稼働曜日を選択してください。',
            'work_days.array' => '稼働曜日の形式が不正です。',
            'work_days.min' => '稼働曜日を1つ以上選択してください。',
            'work_days.*.in' => '稼働曜日の値が不正です。',

            'work_time_from.required' => '稼働時間帯（開始）を入力してください。',
            'work_time_from.date_format' => '稼働時間帯（開始）の形式が不正です。',
            'work_time_to.required' => '稼働時間帯（終了）を入力してください。',
            'work_time_to.date_format' => '稼働時間帯（終了）の形式が不正です。',
            'work_time_to.after' => '稼働時間帯（終了）は開始より後の時刻を入力してください。',

            'note.string' => '備考は文字列で入力してください。',
            'note.max' => '備考は2000文字以内で入力してください。',

            'weekly_hours.required' => '合計週稼働時間を選択してください。',
            'weekly_hours.in' => '合計週稼働時間の値が不正です。',

            'available_start.required' => '開始可能日を選択してください。',
            'available_start.in' => '開始可能日の値が不正です。',
        ];
    }
}


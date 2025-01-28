<x-store-main-layout>
    @php
        $name = $info->name;
        $email = count($info->email) ? $info->email[0]["email"] : "";
        $phone = count($info->phone) ? $info->phone[0]["number"] : "";
    @endphp

    <main class="mx-auto w-[80%] py-10 text-[18px]">
        <x-legal.h1 title="Refund and Returns Policy" />
        <p class="pt-8 text-xl font-bold md:text-2xl">
            Returns And Exchanges, Cancellation, Reimbursement,
            Conditioning/Shipping Rates
        </p>
        <x-legal.h2 content="General Terms" />
        <p class="mt-4 text-[17px]">
            {{ $name }} gladly accepts returns and exchanges with reason:
        </p>
        <x-legal.ol>
            <li>For up to 7 days after the date of purchase</li>
            <li>Returns can be due to damaged/faulty item only</li>
            <li>Proof of damaged/faulty item is mandatory</li>
            <li>Returns that do not meet our policy* will not be accepted:</li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">
            Items returned in non-saleable condition such as:
        </p>
        <x-legal.ol>
            <li>Unsealed</li>
            <li>Swatched/Used (with exception of damaged or faulty items)</li>
            <li>Relabelled</li>
            <li>Refiled</li>
            <li>Pierced</li>
            <li>Partially missing items/threshold based GWPs/Exclusive Sets</li>
        </x-legal.ol>

        <x-legal.ol>
            <li>
                For damaged or faulty items: Bottles must be at least three
                quarters full in order to receive a refund/exchange incase of a
                damaged or defective products
            </li>
            <li>
                We must receive a proof of the damage or fault. Please contact
                our customer service for more information.
            </li>
            <li>
                We do not refund original shipping fees or shipping costs for
                returned products.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">
            To ensure a timely refund or exchange, please contact our customer
            service with the following information: client name, account name,
            original invoice number, item number, item name, quantity, date of
            purchase, and reason for return.
        </p>
        <x-legal.h2 content="Returns and Exchanges" />
        <p class="mt-4 font-semibold">For damaged or defective products:</p>
        <x-legal.ol>
            <li>
                You may return a damaged or defective product in accordance with
                the guidelines in this Returns section. To facilitate the return
                of the product or products, please contact our Customer Support
                Team on WhatsApp at
                <a href="tel:{{ $phone }}">+{{ $phone }}</a>
                or email us at
                <a href="mailto:{{ $email }}">{{ $email }}.</a>
            </li>
            <li>
                Bottles must be at least three quarters full in order to receive
                a refund/exchange in case of a damaged or defective products.
            </li>
            <li>
                You will be optioned to have an exchange product or refund the
                amount.
            </li>
            <li>
                Within seven (7) working days of your receipt, all products must
                be returned to us
            </li>
            <li>
                Items that sustain damage due to typical usage and deterioration
                are not deemed defective or damaged.
            </li>
            <li>
                We will offer to repair defective items whenever practicable.
                The acceptance of returns that are damaged or contaminated is
                possible.
            </li>
            <li>
                Credit will be issued in the original form of payment for
                returns, or exchange product will be shipped within our regular
                business schedule.
                <span class="block font-semibold">
                    Please note that it may take your financial institution
                    approximately 7-14 business days to reflect this
                    transaction.
                </span>
            </li>
            <li>
                Exchanged items due to damage or defect will be shipped at no
                extra charge to the customer.
            </li>
            <li>
                We do not refund original shipping fees or shipping costs for
                returned products. We will notify you via email, phone, or fax
                once your return or exchange has been processed.
            </li>
        </x-legal.ol>
        <p class="mt-4">
            For all inquiries pertaining to the refund policy, please contact
            our Customer Support Team and a representative will be pleased to
            aid you.
        </p>
        <x-legal.h2 content="Cancellation" />
        <p class="mt-4">
            We can issue refunds once a payment has been confirmed and you wish
            to cancel the order if no shipping has been done. Once shipping has
            been processed, we do not refund original shipping fees or shipping
            costs.
        </p>
        <x-legal.ol>
            <li>
                Product must reach us to initiate the refund of cancellation
            </li>
            <li>
                Please note that it may take your financial institution
                approximately 7-14 business days to reflect this transaction.
            </li>
        </x-legal.ol>
        <x-legal.h2 content="Reimbursement" />
        <p class="mt-4">
            We will credit you with the return amount as quickly as possible
            after accepting your return/refund clause. Please note that it may
            take your financial institution approximately
            <span class="font-semibold">
                7-14 business days to reflect this transaction.
            </span>
        </p>
        <x-legal.h2 content="Conditioning/Shipping Rates" />
        <x-legal.ol>
            <li>
                The delivery process will be handled by an external courier
                service “DHL”.
            </li>
            <li>We offer free shipping on local orders that exceed $70.</li>
            <li>
                Delivery within the UAE will occur within one to three business
                days.
            </li>
            <li>
                We use Aramex services for local and international deliveries.
            </li>
            <li>
                We offer free shipping on international orders that exceed $180
                with standard shipping option for the several countries.
            </li>
            <li>
                International deliveries will occur within two to five business
                days.
            </li>
            <li>
                {{ $name }} will make every effort to provide fast and accurate
                service. Feel free to contact us if you have any questions or
                comments.
            </li>
        </x-legal.ol>
    </main>
</x-store-main-layout>

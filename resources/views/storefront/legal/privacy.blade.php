<x-store-main-layout>
    @php
        $name = $info->name;
        $email = count($info->email) ? $info->email[0]["email"] : "";
        $phone = count($info->phone) ? $info->phone[0]["number"] : "";
    @endphp

    <main
        class="mx-auto px-4 py-10 text-[18px] leading-[50px] md:w-[75%] md:px-0"
    >
        <x-legal.h1 title="Privacy Policy" />
        <x-legal.h2 content="General Terms" />
        <p class="mt-4 font-semibold">
            This is the “Privacy Policy” for the ISSASKINTHERAPY.COM
            (“ISSASKINTHERAPY”) website. This policy is exclusive to activities
            conducted by ISSASKINTHERAPY on its website and does not pertain to
            “offline” or extraneous ISSASKINTHERAPY activities.
        </p>
        <p class="mt-4">
            ISSASKINTHERAPY gathers specific anonymous data pertaining to
            website utilization. This information, whether collected alone or in
            conjunction with other data, does not enable users’ personal
            identification. Its sole purpose is to enhance the functionality of
            the website. The anonymous data that ISSASKINTHERAPY may collect may
            consist of details such as the user’s browser type and the duration
            of their visit to the website. On the ISSASKINTHERAPY website, you
            might also be requested to supply personally identifiable
            information, such as your name, address, phone number, and email
            address. This data may be collected through the transmission of
            emails or feedback to ISSASKINTHERAPY in the course of service
            registration or website-based purchases. You always have the option
            to provide personally identifiable information in such
            circumstances.
        </p>
        <x-legal.h2 content="Disclosure And Utilization Of Information" />
        <p class="mt-4">
            We do not sell, transfer, or rent to third parties any personally
            identifiable information collected through the site, unless
            otherwise specified below. The data gathered by our website is
            utilized for the following purposes: order processing, order status
            communication, product or special offer notifications that may be of
            interest to you, and statistical analysis to enhance our website.
            For the purposes of order tracking, processing checks or money
            orders, fulfilling orders, enhancing the functionality of our
            website, conducting statistical and data analyses, delivering
            orders, and sending promotional emails, we may disclose your
            delivery information to third parties. We must, for instance,
            provide the delivery service with your mailing address information
            in order to fulfill your product orders.
        </p>
        <x-legal.h2 content="Ensure Of Security Of Payments" />
        <p class="mt-4">
            Neither personally identifiable information nor credit card details
            will be rented, sold, leased, or rented to third parties.
        </p>
        <x-legal.h2 content="Cookies" />
        <p class="mt-4">
            Cookies are data fragments that are temporarily stored in the
            browser of a user. By utilizing cookies, ISSASKINTHERAPY can
            ascertain whether or not the user has previously visited the
            homepage. Nonetheless, no additional user data is collected.
        </p>
        <x-legal.h2 content="Aggregated Data" />
        <p class="mt-4">
            ISSASKINTHERAPY may utilize “aggregated data” that does not pertain
            to individuals as a means of improving the functionality of our
            website or determining content interest. In addition, if you provide
            OBAGIUAE with feedback or content for publication, we may, with your
            consent, publish your user name or other identifying information.
        </p>
        <p class="mt-4">
            Additionally, ISSASKINTHERAPY may be obligated to divulge personally
            identifiable information to comply with a court order, subpoena, or
            similar request. Such personally identifiable information may also
            be disclosed by ISSASKINTHERAPY in response to a request from law
            enforcement or as otherwise mandated by law. Your personally
            identifiable information might be disclosed to a third party in the
            event that ISSASKINTHERAPY initiates bankruptcy proceedings or if
            its assets or ownership are transferred in the course of proposed or
            completed corporate reorganizations, including mergers and
            acquisitions.
        </p>
        <x-legal.h2 content="Protection" />
        <p class="mt-4">
            By employing a variety of hardware and software methodologies,
            ISSASKINTHERAPY ensures the confidentiality and security of received
            data. Nevertheless, ISSASKINTHERAPY cannot ensure the
            confidentiality of any data shared electronically.
        </p>
        <x-legal.h2 content="Assistant Websites" />
        <p class="mt-4">
            Wherever ISSASKINTHERAPY provides links, such websites’ privacy
            policies are beyond its control. In the event that you divulge any
            information to such third parties, alternative regulations may
            govern the gathering and application of your personal data. We
            strongly advise you to examine the privacy policies of such third
            parties prior to providing them with any information. The policies
            and procedures of third parties do not fall under our ownership.
            Kindly note that our websites might incorporate hyperlinks to
            external websites that are under the ownership and operation of
            third parties. Regarding the information practices of websites that
            are linked to ours, this policy does not apply. These external
            websites have the potential to transmit their own cookies or clear
            GIFs for users, gather data, or request personally identifiable
            information. We have no authority over this information collection.
            It is advisable to communicate inquiries regarding the manner in
            which these entities utilize the information they gather to them
            directly.
        </p>
        <p class="mt-4">
            ISSASKINTHERAPY does not intentionally gather personal information
            from individuals who are juveniles or below the age of 18. The
            ISSASKINTHERAPY website and services are inaccessible to juveniles,
            and individuals who are under the age of 18 are strongly discouraged
            from providing any personal information on the platform. As no
            information is collected regarding minors under the age of 18,
            ISSASKINTHERAPY does not intentionally disclose any personal
            information pertaining to such individuals.
        </p>
        <x-legal.h2 content="Alterations Made To The Privacy Policy" />
        <p class="mt-4">
            The Policies and Terms & Conditions of the ISSASKINTHERAPY website
            would be modified or updated on occasion to conform to new standards
            and requirements. As a result, patrons are advised to regularly
            revisit these sections to remain informed regarding the
            modifications made to the website. The effective date of
            modifications is the date they are published.
        </p>
    </main>
</x-store-main-layout>

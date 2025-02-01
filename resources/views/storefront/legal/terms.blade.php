{{-- TODO: translate --}}

<x-store-main-layout>
    <x-slot name="title">
        <title>{{ getPageTitle(__("legal.terms_conditions")) }}</title>
    </x-slot>
    @php
        $name = $info->name;
        $email = count($info->email) ? $info->email[0]["email"] : "";
        $phone = count($info->phone) ? $info->phone[0]["number"] : "";
    @endphp

    <main class="mx-auto w-[80%] py-10 text-[18px]">
        <x-legal.h1 title="Terms & Conditions" />
        <x-legal.h2 content="General Terms" />
        <x-legal.ol>
            <li>
                We reserve the right to change prices, terms and conditions
                without notice.
            </li>
            <li>All orders must be pre-paid by money order, or credit card</li>
            <li>
                In order to receive wholesale prices, orders must be at least
                $10000.00 worth of retail price
            </li>
            <li>
                A valid professional license number are required to become a
                ISSA distributor and qualify for wholesale prices.
            </li>
            <li>All prices are in US currency.</li>
            <li>All products and services are subject to availability.</li>
        </x-legal.ol>

        <x-legal.h2 content="Quality and Safety" />
        <p class="mt-4">
            {{ $name }}uses the highest quality, pharmaceutical-grade
            ingredients in the market. Our products have a general shelf life of
            12 months. However, due to the nature of antioxidants and certain
            ingredients, we strongly suggest that all products not used
            immediately be stored in a refrigerator in order to slow down the
            oxidation process. Partially used items should also be kept in a
            refrigerator until later use.
        </p>
        <p class="mt-3">
            A patch test is recommended for all topical products. Dab a small
            amount below the ear. You may notice a tingling or warming sensation
            which is normal. If extreme redness, itching, or irritation occurs,
            discontinue use. If irritation persists for more than a few days,
            seek the advise of a doctor. Once compatibilty is established, use
            as directed.
        </p>
        <x-legal.h2 content="Terms of Use" />
        <p class="mt-4 font-semibold">1. Vitadermal FZE operates the “Site”</p>
        <x-legal.ol>
            <li>
                By using the Site you acknowledge and agree to abide by the
                following Terms of Use: This Site is subject to the terms and
                conditions set out in the Site Privacy Policy. The Terms of Use
                for this Site are subject to change at any time. The Terms of
                Use can be changed at any time after you post on the Site, so
                please check this page on a regular basis. We will keep you
                informed of any updates to this Site at the bottom.
            </li>
            <li>
                By using this Site, you agree to comply with the following laws
                and regulations: Your use of this Site may be terminated at any
                time. You may be barred from using the Site for any reason from
                which you have consented. The Site may be subject to legal
                action, including, but not limited to, criminal proceedings,
                against you.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">2. Limited License</p>
        <x-legal.ol>
            <li>
                You’re allowed a constrained, non-exclusive, revocable and
                non-transferable license to utilize and get to the site
                compatible to the necessities and confinements of these terms of
                use.
            </li>
            <li>
                Vitaderm FZE may alter, suspend, or discontinue any perspective
                of the site at any time. Vitaderm FZE may too, without notice,
                force limits on certain features or limit your get to to all or
                parcels of the site. You might have no rights to the exclusive
                program and related documentation, on the off chance that any,
                given to you in arrange to get to the site. Except as given
                within the Terms of Use, you might have no right to specifically
                or in a roundabout way, possess, utilize, credit, offer, lease,
                rent, permit, sublicense, allot, duplicate, interpret, adjust,
                adjust, make strides, or make any modern or subsidiary works
                from, or show, convey, perform, or in any way abuse the site, or
                any of its substance (including software) in entire or in
                portion.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">3. Location Operation</p>
        <x-legal.ol>
            <li>
                United Arab Emirates is our Country of Domicile. Vitaderm FZE
                controls this site from the U.A.E. Vitaderm FZE makes no
                representation that this site is appropriate for utilize in
                other areas. In the event that you employ this site from other
                areas you are dependable for guaranteeing compliance with nearby
                laws. You’ll not utilize, send out or re-export any materials
                from this site in infringement of any appropriate laws or
                regulations, counting, but not restricted to any U.A.E exports
                laws and controls.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">4. Appropriate Law</p>
        <x-legal.ol>
            <li>
                The Laws of the United Arab Emirates should oversee the utilize
                of the Location and the Terms of Utilize, without respects to
                strife of laws principals. All debate emerging in association
                therewith should be listened as it were by a court of competent
                ward in U.A.E.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">5. Multi-currency</p>
        <x-legal.ol>
            <li>
                Estimated Exchange, the shown cost and currency chosen by you,
                will be the same cost and cash charged to the Card and printed
                on the receipt.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">6. Purchases</p>
        <x-legal.ol>
            <li>
                Vitaderm FZE accepts payment by Visa or Mastercard charge and
                credit cards for its items and products. All online buys are too
                represented by the terms and conditions of respective merchant
                service providers. Before completing any transactions, please
                read the user agreement and privacy policy provided by the
                relevant merchant service provider. All prior written and verbal
                agreements and communications pertaining to the topics covered
                in these Terms of Use are superseded by these terms. Any
                modification or waiver of these Terms of Use must be made in
                writing and signed by a Vitaderm FZE authorized officer in order
                to be effective. Refunds will always be applied to the original
                payment method.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">7. Representations by you</p>
        <x-legal.ol>
            <li>
                By accessing this website, you signify, guarantee, and covenant
                that: (a) you are eighteen years of age or older; and (b) no
                part of the content you submit to Vitaderm FZE via the website
                or for its publication will violate, infringe upon, or violate
                the rights of any third party, including trade secrets,
                copyright, trademarks, trade dress, privacy, patents, or other
                proprietary or personal rights. Customers who are minors or
                under the age of eighteen are not permitted to register as users
                of this website, conduct transactions on it, or use it in any
                other way.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">8. Permitted use</p>
        <x-legal.ol>
            <li>
                You acknowledge and agree that you are only permitted to access,
                browse, and print copies of the pages on this site for your own
                personal use. Unless Vitaderm FZE expressly grants you
                permission to do otherwise, you are not permitted to copy,
                download, publish, alter, or otherwise distribute the content on
                this site for any other purpose. Additionally, you consent to
                refrain from deep-linking to the website for any reason unless
                Vitaderm FZE gives you permission to do so. Vitaderm FZE is the
                owner of the software and material on this website.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">9. Your account</p>
        <x-legal.ol>
            <li>
                By using the www.issaskintherapy.com website, you consent to
                bear liability for all actions taken under your account or
                password. You also agree to keep your account and password
                private and to limit access to it from any device. Your failure
                to comply with this section may result in any loss or damage of
                any type, and the Site shall not be held directly or indirectly
                accountable for any such loss or damage.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">10. NO business USE</p>
        <x-legal.ol>
            <li>
                You are not permitted to use this site for any business
                activity, such as making sales of goods or services of any sort.
                Any commercial offers of any type, whether through
                advertisements, solicitations, links, or other forms of
                communication, must have Vitaderm FZE’s prior written
                authorization. If someone breaches this clause, Vitaderm FZE
                will look into it and take appropriate legal action. This may
                involve removing the offending communication from the site and
                preventing the offending party from using the site in the
                future.
            </li>
        </x-legal.ol>
        <p class="mt-4 font-semibold">11. Links and search results</p>
        <x-legal.ol>
            <li>
                The website may automatically generate search results that list
                and/or link to websites operated by third parties across the
                Internet. These websites and the content on them are not under
                the control of Vitaderm FZE. Vitaderm FZE makes no
                representations or warranties regarding the accuracy, legality,
                or inoffensiveness of the material on the sites. Vitaderm FZE
                disavows all responsibility for the information on any
                third-party website and makes no representations or warranties
                on these websites, including that they won’t harm your computer
                or contain viruses. By using the Site to look for content or
                create a link to another website, you acknowledge and accept
                that Vitaderm FZE will not be held liable for any losses or
                damages you may incur as a result of using the Site to look for
                content or create a link to another website. Kindly contact info
                @issaskintherapy.com
                to report any issues you may be having with a link from the
                Site.
            </li>
        </x-legal.ol>
        <x-legal.h2 content="Copyright Policy" />
        <x-legal.ol>
            <li>
                Any user who utilizes this site to transmit copyrighted
                information illegally without a license, express consent,
                legitimate defense, or fair use exemption may have their
                privileges terminated by Vitaderm FZE. You represent and warrant
                that any information you submit to this website does not violate
                any third parties’ copyrights or other rights.
            </li>
            <li>
                The Online Materials are and shall continue to be Vitaderm FZE’s
                sole property. Vitaderm FZE will exclusively hold all rights,
                titles, and interests in and to the online materials going
                forward. Except as expressly and specifically specified in the
                Terms of Use, you will never have the right, either directly or
                indirectly, to own, use, copy, loan, sell, rent, lease, license,
                sublicense, redistribute, assign, or otherwise convey the
                On-line Materials or any rights thereto. You will not acquire
                any rights, title, or interests from these Terms of Use other
                than those of a license with the express rights and subject to
                the restrictions stated above. You are not granted the
                permission to use the online materials in any way that would
                compete with Vitaderm FZE or to use them to make products for
                resale in any way by these terms of use.
            </li>
            <li>
                You understand and agree that Vitaderm FZE will be the only
                owner of all rights, titles, and interests pertaining to any
                translation, improvement, alteration, adaptation, and derivative
                work done by or on behalf of you about the online materials. You
                shall execute, or cause to be executed, any instrument that may
                be required to transfer these rights, titles, or interests to
                Vitaderm FZE or to perfect these rights, titles, or interests in
                Vitaderm FZE’s name, at Vitaderm FZE’s request. Limitation of
                damages and disclaimer of warranty. Regarding the availability,
                accuracy, validity, reliability, and content of these pages
                and/or the website, Vitaderm FZE makes no representations or
                warranties of any kind, either express or implied. This
                includes, but is not limited to, implied warranties of
                merchantability, non-infringement, and fitness for a particular
                purpose, as well as warranties of title and non-infringement and
                non-violation of other rights. Additionally, Vitaderm FZE
                disclaims any liability with regard to the accuracy or
                dependability of any advice, opinion, statement, or other
                information submitted, displayed, or uploaded by users through
                the website. Even though Vitaderm FZE LLC has been informed of
                the possibility of such damages, Vitaderm FZE LLC shall not be
                liable for any direct, indirect, incidental, special,
                consequential, or lost profits, or for business interruption
                resulting from the use of or inability to use this site. THE
                ABOVE LIMITATIONS OR EXCLUSIONS MAY NOT APPLY TO YOU AS SOME
                JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF CERTAIN WARRANTIES
                OR LIMITATIONS OF LIABILITY. In such a scenario, Vitaderm FZE’s
                liability would be restricted to the maximum amount allowed by
                law.
            </li>
        </x-legal.ol>
        <x-legal.h2 content="Violation of Terms of Use" />
        <x-legal.ol>
            <li>
                You acknowledge and agree that Vitaderm FZE may, in its sole
                discretion, and without prior notice, terminate your access to
                the Site, exercise any other available remedy, and remove any
                information provided by unauthorized users if Vitaderm FZE
                believes that the information you provide has violated or is
                inconsistent with these Terms of Use, or if it takes legal
                action against Vitaderm FZE, any third party, or the law. You
                acknowledge that money damages might not be enough to compensate
                Vitaderm FZE for breaches of these terms of use, and you agree
                to an injunction or other equitable remedy in lieu of monetary
                damages. In addition, you agree that Vitaderm FZE may be obliged
                by law or subpoena to disclose user information about you.
            </li>
        </x-legal.ol>

        <x-legal.h2 content="Indemnity" />
        <x-legal.ol>
            <li>
                By using the Site, including to upload content or other
                information, to provide links to other websites, or to violate
                the Terms of Use, you agree to indemnify and hold Vitaderm FZE,
                its subsidiaries, affiliates, officers, agents, and other
                partners and employees harmless from any loss, liability, claim,
                or demand made by any third party due to or arising out of or
                relating to your use of the Site.
            </li>
        </x-legal.ol>

        <x-legal.h2 content="License granted to you" />
        <x-legal.ol>
            <li>
                By supplying Vitaderm FZE with materials, such as by uploading
                or submitting content for use on the Site, you represent and
                guarantee that Vitaderm FZE has been granted an irreversible
                worldwide license in all languages to use and exploit all or any
                portion of the content and materials you have provided. At its
                sole discretion, Vitaderm FZE may, by any means now known or
                hereafter invented, publish and distribute any such submitted
                content or materials. For any alleged or actual infringement or
                misappropriation of any proprietary rights in any communication,
                content, or material provided to Vitaderm FZE, you hereby agree
                to waive all claims and to have no recourse against Vitaderm
                FZE. Your correspondence or any materials you send to Vitaderm
                FZE will be considered non-proprietary and non-confidential.
                Vitaderm FZE may use your materials or communications for any
                purpose, including but not restricted to product or service
                development, creation, manufacturing, and marketing.
            </li>
            <li>
                Advertisements and/or sponsorships may be found on the website.
                The only people who can guarantee that the content submitted for
                the Site is truthful and complies with all relevant laws are the
                advertisers and/or sponsors that offer these sponsorships and
                adverts. The actions or inactions of any advertising or sponsor
                are not the responsibility of Vitaderm FZE.
            </li>
        </x-legal.ol>

        <x-legal.h2 content="Severability" />
        <x-legal.ol>
            <li>
                The remaining terms of the Terms of Use will remain in full
                force and effect even if one or more of them are found to be
                illegal, void, or unenforceable.
            </li>
        </x-legal.ol>

        <x-legal.h2 content="Prices and Order of Acceptance" />
        <x-legal.ol>
            <li>
                Please be aware that orders occasionally cannot be fulfilled for
                a variety of reasons. The Site retains the right, at any time
                and for any reason, to reject or cancel any order. Before we
                accept the order, we might need more information from you, such
                as your address and phone number, among other things.
            </li>
        </x-legal.ol>

        <p class="mt-8 text-2xl font-bold italic">
            This Agreement’s headings and section headings are for convenience
            only; they do not define, restrict, or expand any of its provisions.
        </p>
    </main>
</x-store-main-layout>
